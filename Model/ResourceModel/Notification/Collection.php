<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Model\ResourceModel\Notification;

use Hawksama\ProductRuleNotifier\Model\Notice as Model;
use Hawksama\ProductRuleNotifier\Model\ResourceModel\AbstractCollection;
use Hawksama\ProductRuleNotifier\Model\ResourceModel\Notification as Resource;
use Hawksama\ProductRuleNotifier\Api\Data\NoticeInterface;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'notice_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'hawksama_notice_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'notice_collection';

    /**
     * Perform operations after collection load
     */
    protected function _afterLoad(): self
    {
        $entityMetadata = $this->metadataPool->getMetadata(NoticeInterface::class);

        $this->performAfterLoad('hawksama_notice_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, Resource::class);
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['notice_id'] = 'main_table.notice_id';
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
        $entityMetadata = $this->metadataPool->getMetadata(NoticeInterface::class);
        $this->joinStoreRelationTable('hawksama_notice_store', $entityMetadata->getLinkField());
    }
}
