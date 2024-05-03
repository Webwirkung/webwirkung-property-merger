<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Service\Product;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class Option
{
  private Context $context;

  private EntityRepository $productOptionRepository;

  public function __construct(EntityRepository $productOptionRepository)
  {
    $this->context = Context::createDefaultContext();
    $this->productOptionRepository = $productOptionRepository;
  }

  public function getContext(): Context
  {
    return $this->context;
  }

  public function delete(string $productId, string $optionId): void
  {
    $this->productOptionRepository->delete([
      [
          'productId' => $productId,
          'optionId' => $optionId,
      ]
    ], $this->getContext());
  }
}
