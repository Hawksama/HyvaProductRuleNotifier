<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Query\Notification;

use Hawksama\ProductRuleNotifier\Api\Data\NotificationInterface;
use Hawksama\ProductRuleNotifier\Api\Data\NotificationSearchResultsInterface;
use Hawksama\ProductRuleNotifier\Mapper\NotificationDataMapper;
use Hawksama\ProductRuleNotifier\Model\ResourceModel\Notification\Collection;
use Hawksama\ProductRuleNotifier\Model\ResourceModel\Notification\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;

/**
 * Get Notification list by search criteria query.
 */
class GetListQuery
{
    public function __construct(
        private readonly CollectionProcessorInterface  $collectionProcessor,
        private readonly CollectionFactory $entityCollectionFactory,
        private readonly NotificationDataMapper $entityDataMapper,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly SearchResultsInterfaceFactory $searchResultFactory
    ) {
    }

    /**
     * Get Notification list by search criteria.
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->entityCollectionFactory->create();
        $searchCriteria = $searchCriteria ?? $this->searchCriteriaBuilder->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var NotificationInterface[] $entityDataObjects */
        $entityDataObjects = $this->entityDataMapper->map($collection);

        /** @var NotificationSearchResultsInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($entityDataObjects);
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }
}
