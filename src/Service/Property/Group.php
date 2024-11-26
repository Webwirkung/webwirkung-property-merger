<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Service\Property;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;

class Group
{
  private readonly Context $context;

  public function __construct(private readonly EntityRepository $groupRepository)
  {
    $this->context = Context::createDefaultContext();
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
    $criteria = (!empty($ids) ? (new Criteria($ids)) : new Criteria())
      ->addAssociation('options.group')
      ->addSorting(new FieldSorting('name', FieldSorting::ASCENDING));

    return $this->groupRepository
        ->search($criteria, $this->getContext())
        ->getElements();
  }

  public function delete(string $id): void
  {
    $this->groupRepository->delete([
      [
          'id' => $id,
      ]
    ], $this->getContext());
  }
}
