<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Command\Property\Group;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WebwirkungPropertyMerger\Service\Property\Group;
use Symfony\Component\Console\Helper\Table;

class Show extends Command
{
  private Group $propertyGroupService;

  protected static $defaultName = 'webwirkung:property-list';

  public function __construct(Group $propertyGroupService)
  {
      parent::__construct();
      $this->propertyGroupService = $propertyGroupService;
  }

  protected function configure(): void
  {
      $this ->setDescription('Show all properties groups with ids.');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {

    $propertyGroups = $this->propertyGroupService->getAll();

    if (count($propertyGroups) > 0) {
      $table = new Table($output);
      $table->setHeaders(['Group ID', 'Group name', 'Number of options']);

      $x = 0;
      foreach ($propertyGroups as $group) {
        $table->setRow($x++, [$group->getId(), $group->getName(), count($group->getOptions())]);
      }

      $table->render();
    } else {
      $output->writeln('You don\'t have defined property groups.');
    }

    return Command::SUCCESS;
  }
}
