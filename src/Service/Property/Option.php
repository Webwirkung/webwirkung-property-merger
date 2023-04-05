<?php

declare(strict_types=1);

namespace WebwirkungPropertyMerger\Service\Property;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class Option
{
  private Context $context;

  private EntityRepository $groupOptionRepository;

  public function __construct(EntityRepository $groupOptionRepository)
  {
    $this->context = Context::createDefaultContext();
    $this->groupOptionRepository = $groupOptionRepository;
  }

  public function getContext(): Context
  {
    return $this->context;
  }

  public function update(string $id, string $item)
  {
    $this->groupOptionRepository->update([
      [
          'id' => $item,
          'groupId' => $id,
      ]
    ], $this->getContext());
  }

  public function delete(string $id)
  {
    $this->groupOptionRepository->delete([
      [
          'id' => $id,
      ]
    ], $this->getContext());
  }
}
