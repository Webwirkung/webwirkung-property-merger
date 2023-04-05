<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Service\Property;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class Product
{
  private Context $context;

  private EntityRepository $productPropertyRepository;

  public function __construct(EntityRepository $productPropertyRepository)
  {
    $this->context = Context::createDefaultContext();
    $this->productPropertyRepository = $productPropertyRepository;
  }

  public function getContext(): Context
  {
    return $this->context;
  }

  public function delete(string $id, string $optionId): void
  {
    $this->productPropertyRepository->delete([
      [
        'productId' => $id,
        'optionId' => $optionId,
      ]
    ], $this->getContext());
  }
}
