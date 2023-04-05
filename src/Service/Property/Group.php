<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Service\Property;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Content\Property\PropertyGroupEntity;
use WebwirkungPropertyMerger\Service\Product\Product;

class Group
{
  private Context $context;

  private EntityRepository $groupRepository;

  private Option $groupOptionService;

  private Product $productService;

  public function __construct(EntityRepository $groupRepository, Option $groupOptionService, Product $productService)
  {
    $this->context = Context::createDefaultContext();
    $this->groupRepository = $groupRepository;
    $this->groupOptionService = $groupOptionService;
    $this->productService = $productService;
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

  public function delete(string $id)
  {
    $this->groupRepository->delete([
      [
          'id' => $id,
      ]
    ], $this->getContext());
  }

  public function prepareToMerge(PropertyGroupEntity $sourceGroup, PropertyGroupEntity $destinationGroup): array
  {
    $sourceGroupOptions = $sourceGroup->getOptions()->getElements();
    $destinationGroupOptions = $destinationGroup->getOptions()->getElements();

    $mergeProperties = [];
    foreach ($sourceGroupOptions as $option) {
      $isInDestination = array_filter($destinationGroupOptions, fn($item) => $item->getName() === $option->getName());
      if (! empty($isInDestination)) {
        $products = $this->productService->findByGroupOption($option->getId());
        foreach ($products as $product) {
          $propertyIds = $product->getPropertyIds();
          $changedPropertyIds = array_map(fn($item) => $item === $option->getId() ? ['id' => reset($isInDestination)->getId()] : ['id' => $item], $propertyIds);
          $this->productService->updateProperty($product->getId(), $option->getId(), array_unique($changedPropertyIds, SORT_REGULAR));
        }
        $this->groupOptionService->delete($option->getId());
        continue;
      }

      array_push($mergeProperties, $option->getId());
    }
    return $mergeProperties;
  }
}
