<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Ui\DataProvider\Form;

use Hawksama\HyvaProductRuleNotifier\Model\ResourceModel\Notification\Collection;
use Hawksama\HyvaProductRuleNotifier\Model\ResourceModel\Notification\CollectionFactory;
use Hawksama\HyvaProductRuleNotifier\Model\Notification as EntityModel;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

/**
 * Data provider for the notification form.
 *
 * This class is responsible for providing data for the notification form,
 * including the notification collection and any additional data required
 * for the form to function correctly.
 */
class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $entityCollectionFactory,
        protected DataPersistorInterface $dataPersistor,
        public array $loadedData = [],
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $entityCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    public function getData()
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var EntityModel $entity */
        foreach ($items as $entity) {
            $this->loadedData[$entity->getId()] = $entity->getData();
        }

        $data = $this->dataPersistor->get('hawksama_notification_data');
        if (!empty($data)) {
            $entity = $this->collection->getNewEmptyItem();
            $entity->setData($data);
            $this->loadedData[$entity->getId()] = $entity->getData();
            $this->dataPersistor->clear('hawksama_notification_data');
        }

        return $this->loadedData;
    }
}
