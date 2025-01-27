<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Model\ResourceModel\Notification\Relation\Store;

use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationInterface;
use Hawksama\HyvaProductRuleNotifier\Model\Notification as Model;
use Hawksama\HyvaProductRuleNotifier\Model\ResourceModel\Notification as Resource;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Magento\Framework\EntityManager\MetadataPool;

class SaveHandler implements ExtensionInterface
{
    /**
     * @param MetadataPool $metadataPool
     * @param Resource $resource
     */
    public function __construct(
        private readonly MetadataPool $metadataPool,
        private readonly Resource $resource
    ) {
    }

    /**
     * @param Model $entity
     * @param array $arguments
     * @throws \Exception
     */
    public function execute($entity, $arguments = []): object
    {
        $entityMetadata = $this->metadataPool->getMetadata(NotificationInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $connection = $entityMetadata->getEntityConnection();

        /** @var Model $entity */
        $oldStores = $this->resource->lookupStoreIds((int)$entity->getId());
        $newStores = (array)$entity->getStores();

        $table = $this->resource->getTable('hawksama_notification_store');

        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                'store_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    'store_id' => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }

        return $entity;
    }
}
