<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Service\Property;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class Group
{
  private Context $context;

  private EntityRepository $groupRepository;

  public function __construct(EntityRepository $groupRepository)
  {
    $this->context = Context::createDefaultContext();
    $this->groupRepository = $groupRepository;
  }

  public function getContext(): Context
  {
    return $this->context;
  }

  public function getAll(): array
  {
    return $this->getByIds();
  }

  public function getByIds(array $ids = []): array
  {
    $criteria = (new Criteria($ids))
                    ->addAssociation('options.group');

    return $this->groupRepository
        ->search($criteria, $this->getContext())
        ->getElements();
  }
}
