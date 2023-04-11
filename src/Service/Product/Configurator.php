<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Service\Product;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class Configurator
{
  private Context $context;

  private EntityRepository $productConfigurationRepository;

  public function __construct(EntityRepository $productConfigurationRepository)
  {
    $this->context = Context::createDefaultContext();
    $this->productConfigurationRepository = $productConfigurationRepository;
  }

  public function getContext(): Context
  {
    return $this->context;
  }

  public function delete(string $id): void
  {
    $this->productConfigurationRepository->delete([
      [
          'id' => $id,
      ]
    ], $this->getContext());
  }
}
