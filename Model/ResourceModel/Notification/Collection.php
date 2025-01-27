<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Model\ResourceModel\Notification;

use Hawksama\ProductRuleNotifier\Model\Notification as Model;
use Hawksama\ProductRuleNotifier\Model\ResourceModel\AbstractCollection;
use Hawksama\ProductRuleNotifier\Model\ResourceModel\Notification as Resource;
use Hawksama\ProductRuleNotifier\Api\Data\NotificationInterface;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'notification_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'hawksama_notification_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'notification_collection';

    /**
     * Perform operations after collection load
     */
    protected function _afterLoad(): self
    {
        $entityMetadata = $this->metadataPool->getMetadata(NotificationInterface::class);

        $this->performAfterLoad('hawksama_notification_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, Resource::class);
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['notification_id'] = 'main_table.notification_id';
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     */
    public function addStoreFilter($store, $withAdmin = true): self
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }

    /**
     * Join store relation table if there is store filter
     */
    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(NotificationInterface::class);
        $this->joinStoreRelationTable('hawksama_notification_store', $entityMetadata->getLinkField());
    }
}
