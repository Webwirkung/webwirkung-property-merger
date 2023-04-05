<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Service\Product;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use WebwirkungPropertyMerger\Service\Property\Product as PropertyProduct;

class Product
{
  private Context $context;

  private EntityRepository $productRepository;

  private PropertyProduct $productPropertyService;

  public function __construct(EntityRepository $productRepository, PropertyProduct $productPropertyService)
  {
    $this->context = Context::createDefaultContext();
    $this->productRepository = $productRepository;
    $this->productPropertyService = $productPropertyService;
  }

  public function getContext(): Context
  {
    return $this->context;
  }

  public function findByGroupOption(string $id)
  {
    $criteria = new Criteria();
    $criteria->addFilter(
        new ContainsFilter('propertyIds', $id),
    );

    return $this->productRepository->search($criteria, $this->getContext());
  }

  public function updateProperty(string $id, string $sourceId, array $data)
  {
    $this->productPropertyService->delete($id, $sourceId);

    $this->productRepository->update([
      [
          'id' => $id,
          'properties' => $data,
      ]
    ], $this->getContext());
  }
}
