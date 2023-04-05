<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Command\Property\Group\Option;

use Enqueue\Util\UUID as UtilUUID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use WebwirkungPropertyMerger\Service\Property\Group;
use WebwirkungPropertyMerger\Service\Property\Option;
use Shopware\Core\Framework\Uuid\Uuid;

class Merge extends Command
{
  private Group $propertyGroupService;

  private Option $propertyGroupOptionService;

  public function __construct(Group $propertyGroupService, Option $propertyGroupOptionService) {
      parent::__construct();
      $this->propertyGroupService = $propertyGroupService;
      $this->propertyGroupOptionService = $propertyGroupOptionService;
  }

  protected static $defaultName = 'webwirkung:property-merge';

  protected function configure(): void
  {
      $this
            ->addArgument('source', InputArgument::REQUIRED, 'origin/source - Which options do you merge?')
            ->addArgument('destination', InputArgument::REQUIRED, 'Destination of the merge action')
            ->setDescription('Merge your properties fields easily.')
        ;
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $source = $input->getArgument('source');
    $destination = $input->getArgument('destination');

    if (! UUID::isValid($source)) {
      $output->writeln('<error> The source ID is not valid. </error>');
      return Command::FAILURE;
    }

    if (! UUID::isValid($destination)) {
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

    $preparedPackage = $this->propertyGroupService->prepareToMerge($propertyGroups[$source], $propertyGroups[$destination]);

    foreach ($preparedPackage as $item) {
      // $this->propertyGroupOptionService->update($destination, $item);
    }

    // $this->propertyGroupService->delete($source);

    return Command::SUCCESS;
  }
}
