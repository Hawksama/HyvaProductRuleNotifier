<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Model\ResourceModel\Notification\Relation\Store;

use Hawksama\HyvaProductRuleNotifier\Model\ResourceModel\Notification as Resource;
use Hawksama\HyvaProductRuleNotifier\Model\Notification as Model;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

class ReadHandler implements ExtensionInterface
{
    /**
     * @param Resource $resourceNotification
     */
    public function __construct(
        private readonly Resource $resourceNotification
    ) {
    }

    /**
     * @param Model $entity
     * @param array $arguments
     */
    public function execute($entity, $arguments = []): object
    {
        /** @var Model $entity */
        if ($entity->getId()) {
            $stores = $this->resourceNotification->lookupStoreIds((int)$entity->getId());
            $entity->setData('store_id', $stores);
        }
        return $entity;
    }
}
