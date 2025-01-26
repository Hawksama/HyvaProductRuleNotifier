<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Model\ResourceModel\Notification\Relation\Store;

use Hawksama\Notice\Model\ResourceModel\Notification as Resource;
use Hawksama\Notice\Model\Notice as Model;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

class ReadHandler implements ExtensionInterface
{
    /**
     * @param Resource $resourceNotice
     */
    public function __construct(
        private readonly Resource $resourceNotice
    ) {
    }

    /**
     * @param Model $entity
     * @param array $arguments
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = []): object
    {
        /** @var Model $entity */
        if ($entity->getId()) {
            $stores = $this->resourceNotice->lookupStoreIds((int)$entity->getId());
            $entity->setData('store_id', $stores);
            $entity->setData('stores', $stores);
        }
        return $entity;
    }
}
