<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Command\Property\Group\Option;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Symfony\Component\Console\Input\InputArgument;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class Merge extends Command
{
  private $propertyGroup;
  private $propertyGroupOption;

  public function __construct(EntityRepositoryInterface $propertyGroup, EntityRepositoryInterface $propertyGroupOption) {
      parent::__construct();
      $this->propertyGroup = $propertyGroup;
      $this->propertyGroupOption = $propertyGroupOption;
  }

  protected static $defaultName = 'webwirkung:property-merge';

  protected function configure(): void
  {
      $this
            ->addArgument('o', InputArgument::REQUIRED, 'origin/source - Which options do you merge?')
            ->addArgument('d', InputArgument::REQUIRED, 'Destination of the merge action')
            ->setDescription('Merge your properties fields easily.')
        ;
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    var_dump($input->getArgument('o'));
    var_dump($input->getArgument('d'));

    // $criteria = (new Criteria())->addAssociation('options.group');
    // $propertyGroup = $this->propertyGroup->search($criteria, Context::createDefaultContext());
    // $propertyGroupEntities = $propertyGroup->getElements();

    // foreach ($propertyGroupEntities as $entity) {
    //   $duplicate = array_filter($propertyGroupEntities, fn($item) => $entity->getName() === $item->getName());
    //   foreach ($duplicate as $d) {
    //     if ($d->getId() !== $entity->getId()) {
    //       $changed = array_map(fn($item) => $item->setGroupId($d->getId()), $d->getOptions());
    //       var_dump($changed);
    //       die;
    //     }
    //   }

    // }


    return 0;
  }
}
