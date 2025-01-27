<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Model;

use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationSearchResultsInterface;
use Hawksama\HyvaProductRuleNotifier\Api\NotificationRepositoryInterface;
use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationInterface;
use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationSearchResultsInterfaceFactory as SearchResultsInterfaceFactory;
use Hawksama\HyvaProductRuleNotifier\Model\Notification as Model;
use Hawksama\HyvaProductRuleNotifier\Model\NotificationFactory as ModelFactory;
use Hawksama\HyvaProductRuleNotifier\Model\ResourceModel\Notification as Resource;
use Hawksama\HyvaProductRuleNotifier\Model\ResourceModel\Notification\Collection;
use Hawksama\HyvaProductRuleNotifier\Model\ResourceModel\Notification\CollectionFactory;
use Hawksama\HyvaProductRuleNotifier\Mapper\NotificationDataMapper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\EntityManager\HydratorInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class NotificationRepository implements NotificationRepositoryInterface
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private readonly Resource                      $resource,
        private readonly ModelFactory                  $notificationFactory,
        private readonly CollectionFactory             $collectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory,
        private readonly StoreManagerInterface         $storeManager,
        private readonly NotificationDataMapper        $entityDataMapper,
        private readonly CollectionProcessor           $collectionProcessor,
        private readonly HydratorInterface             $hydrator
    ) {
    }

    /**
     * Save Notification data
     *
     * @param NotificationInterface $notification
     * @return NotificationInterface
     * @throws CouldNotSaveException
     */
    public function save(NotificationInterface $notification): NotificationInterface
    {
        if (empty($notification->getStoreId())) {
            $notification->setStoreId((int) $this->storeManager->getStore()->getId());
        }

        if ($notification->getNotificationId()
            && $notification instanceof Notification
            && !$notification->getOrigData()
        ) {
            $existingNotification = $this->getById($notification->getId());
            $extractedData = $this->hydrator->extract($notification);
            $notification = $this->hydrator->hydrate(
                $existingNotification,
                $extractedData
            );
        }

        if (!$notification instanceof Model) {
            throw new CouldNotSaveException(__('Invalid notification instance.'));
        }

        try {
            $this->resource->save($notification);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $notification;
    }

    /**
     * Load Block data by given Block Identity
     *
     * @param string $notificationId
     * @return NotificationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($notificationId): NotificationInterface
    {
        $notification = $this->notificationFactory->create();
        $this->resource->load($notification, $notificationId);
        if (!$notification->getId()) {
            throw new NoSuchEntityException(__('The entity with the "%1" ID doesn\'t exist.', $notificationId));
        }
        return $notification;
    }

    /**
     * Load Block data collection by given search criteria
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return NotificationSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $entityDataObjects = $this->entityDataMapper->map($collection);

        /** @var NotificationSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($entityDataObjects);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Block
     *
     * @param NotificationInterface $notification
     * @throws CouldNotDeleteException
     */
    public function delete(NotificationInterface $notification): bool
    {
        if (!$notification instanceof Model) {
            throw new CouldNotDeleteException(__('Invalid notification instance.'));
        }

        try {
            $this->resource->delete($notification);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Block by given Block Identity
     *
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(string $notificationId): bool
    {
        return $this->delete($this->getById($notificationId));
    }
}
