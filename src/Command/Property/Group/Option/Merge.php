<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Command\Property\Group\Option;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WebwirkungPropertyMerger\Service\Property\Group;
use WebwirkungPropertyMerger\Service\Property\Option;
use Shopware\Core\Framework\Uuid\Uuid;
use WebwirkungPropertyMerger\Service\Product\Product;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputOption;

class Merge extends Command
{
  private Group $propertyGroupService;

  private Option $propertyGroupOptionService;

  private Product $productService;

  public function __construct(Group $propertyGroupService, Option $propertyGroupOptionService, Product $productService) {
      parent::__construct();
      $this->propertyGroupService = $propertyGroupService;
      $this->propertyGroupOptionService = $propertyGroupOptionService;
      $this->productService = $productService;
  }

  protected static $defaultName = 'webwirkung:property-merge';

  protected function configure(): void
  {
      $this
            ->addOption('source', 's', InputOption::VALUE_REQUIRED, 'origin/source - Which options do you merge?')
            ->addOption('destination', 'd', InputOption::VALUE_REQUIRED, 'Destination of the merge action')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run without modifying the database.')
            ->setDescription('Merge your properties fields easily.')
        ;
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $source = $input->getOption('source');
    $destination = $input->getOption('destination');
    $dryRun = $input->getOption('dry-run');

    if (! $source || ! UUID::isValid($source)) {
      $output->writeln('<error> The source ID is not valid. </error>');
      return Command::FAILURE;
    }

    if (! $destination || ! UUID::isValid($destination)) {
      $output->writeln('<error> The destination ID is not valid. </error>');
      return Command::FAILURE;
    }

    $propertyGroups = $this->propertyGroupService->getByIds([$source, $destination]);

    if (0 === count($propertyGroups)) {
      $output->writeln('<bg=yellow> You don\'t have defined property groups. </>');
      return Command::SUCCESS;
    }

    if (1 === count($propertyGroups)) {
      $output->writeln(sprintf('<bg=yellow> The %s group not found. </>', (array_key_exists($source, $propertyGroups) ? 'destination' : 'source')));
      return Command::SUCCESS;
    }

    $sourceGroupOptions = $propertyGroups[$source]->getOptions()->getElements();
    $destinationGroupOptions = $propertyGroups[$destination]->getOptions()->getElements();

    $table = new Table($output);
    $table->setHeaders(['Property ID', 'Property name', 'Action']);

    $x = 0;
    foreach ($sourceGroupOptions as $option) {
      $inDestination = array_filter($destinationGroupOptions, fn($item) => $item->getName() === $option->getName());

      if (! empty($inDestination)) {
        if (! $dryRun) {
          $products = $this->productService->findByGroupOption($option->getId());
          $inDestination = reset($inDestination);

          foreach ($products as $product) {
            $changedPropertyIds = array_map(fn($item) => $item === $option->getId() ? ['id' => $inDestination->getId()] : ['id' => $item], $product->getPropertyIds());
            $this->productService->updateProperty($product->getId(), $option->getId(), array_unique($changedPropertyIds, SORT_REGULAR));
          }

          $productWithOptions = $this->productService->findOptions($option->getId());

          foreach ($productWithOptions as $productOption) {
            $this->productService->updateOptions($productOption->getId(), $option->getId(), $inDestination);
          }

          $productConfigurations = $this->productService->findConfiguration($option->getId());

          foreach ($productConfigurations as $productConfiguration) {
            $configration = array_filter($productConfiguration->getConfiguratorSettings()->getElements(), fn($item) => $item->getOptionId() === $option->getId());
            $this->productService->updateConfigurator($productConfiguration->getId(), $inDestination, reset($configration));
          }

          $this->propertyGroupOptionService->delete($option->getId());
        }

        $table->setRow($x++, [$option->getId(), $option->getName(), 'Overriden']);
        continue;
      }

      if (! $dryRun) {
        $this->propertyGroupOptionService->update($destination, $option->getId());
      }
      $table->setRow($x++, [$option->getId(), $option->getName(), 'Merged']);
    }

    if (! $dryRun) {
      $this->propertyGroupService->delete($source);
    }

    $table->render();

    return Command::SUCCESS;
  }
}
