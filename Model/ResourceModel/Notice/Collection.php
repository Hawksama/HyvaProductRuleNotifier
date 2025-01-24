<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Model\ResourceModel\Notice;

use Hawksama\Notice\Model\Notice;
use Hawksama\Notice\Model\ResourceModel\AbstractCollection;
use Hawksama\Notice\Model\ResourceModel\Notice as Resource;
use Hawksama\Notice\Api\Data\NoticeInterface;

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
     *
     * @return $this
     */
    protected function _afterLoad()
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
		$this->_init(Notice::class, Resource::class);
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['notice_id'] = 'main_table.notice_id';
	}

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true): static
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(NoticeInterface::class);
        $this->joinStoreRelationTable('hawksama_notice_store', $entityMetadata->getLinkField());
    }
}
