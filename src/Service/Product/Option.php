<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Service\Product;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class Option
{
  private readonly Context $context;

  public function __construct(private readonly EntityRepository $productOptionRepository)
  {
    $this->context = Context::createDefaultContext();
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
