<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Model\ResourceModel;

use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationInterface;
use Hawksama\HyvaProductRuleNotifier\Model\Notification as Model;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use RuntimeException;

class Notification extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'hawksama_notification_resource_model';

    public function __construct(
        Context $context,
        private readonly EntityManager $entityManager,
        readonly MetadataPool $metadataPool,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('hawksama_notification', NotificationInterface::NOTIFICATION_ID);
        $this->_useIsObjectNew = true;
    }

    /**
     * @inheritDoc
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(NotificationInterface::class)->getEntityConnection();
    }

    /**
     * Load an object
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param string $field field to load by (defaults to model id)
     * @return $this
     * @throws LocalizedException
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $blockId = $this->getNotificationId($object, $value, $field);
        if ($blockId) {
            $this->entityManager->load($object, $blockId);
        }
        return $this;
    }

    /**
     * Get notification id.
     *
     * @throws LocalizedException
     * @throws \Exception
     */
    private function getNotificationId(AbstractModel $object, mixed $value, string $field = null): string
    {
        $entityMetadata = $this->metadataPool->getMetadata(NotificationInterface::class);
        if (!is_numeric($value) && $field === null) {
            $field = 'identifier';
        } elseif (!$field) {
            $field = $entityMetadata->getIdentifierField();
        }
        $entityId = $value;
        if ($field != $entityMetadata->getIdentifierField() || $object->getStoreId()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                ->limit(1);
            $connection = $this->getConnection();
            if ($connection) {
                $result = $connection->fetchCol($select);
                $entityId = count($result) ? $result[0] : false;
            }
        }
        return $entityId;
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param AbstractModel $object
     * @return Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(NotificationInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = [(int)$object->getStores(), Store::DEFAULT_STORE_ID];

            $select->join(
                ['hns' => $this->getTable('hawksama_notification_store')],
                $this->getMainTable() . '.' . $linkField . ' = hns.notification_id',
                ['store_id']
            )
                ->where('enabled = ?', 1)
                ->where('hns.store_id in (?)', $stores)
                ->order('store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    /**
     * Get store ids to which specified item is assigned
     */
    public function lookupStoreIds(int $id): array
    {
        $connection = $this->getConnection();

        if (!$connection) {
            throw new RuntimeException('Database connection is not available.');
        }

        $entityMetadata = $this->metadataPool->getMetadata(NotificationInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['hns' => $this->getTable('hawksama_notification_store')], 'store_id')
            ->join(
                ['hn' => $this->getMainTable()],
                'hns.' . $linkField . ' = hn.' . $linkField,
                []
            )
            ->where('hn.' . $entityMetadata->getIdentifierField() . ' = :notification_id');

        return $connection->fetchCol($select, ['notification_id' => $id]);
    }

    /**
     * Save an object.
     *
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }
}
