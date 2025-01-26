<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Model;

use Hawksama\ProductRuleNotifier\Api\Data\NoticeSearchResultsInterface;
use Hawksama\ProductRuleNotifier\Api\NoticeRepositoryInterface;
use Hawksama\ProductRuleNotifier\Api\Data\NoticeInterface;
use Hawksama\ProductRuleNotifier\Api\Data\NoticeInterfaceFactory;
use Hawksama\ProductRuleNotifier\Api\Data\NoticeSearchResultsInterfaceFactory;
use Hawksama\ProductRuleNotifier\Model\Notice as Model;
use Hawksama\ProductRuleNotifier\Model\NoticeFactory as ModelFactory;
use Hawksama\ProductRuleNotifier\Model\ResourceModel\Notification as Resource;
use Hawksama\ProductRuleNotifier\Model\ResourceModel\Notification\Collection;
use Hawksama\ProductRuleNotifier\Model\ResourceModel\Notification\CollectionFactory;
use Hawksama\ProductRuleNotifier\Mapper\NoticeDataMapper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\EntityManager\HydratorInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class NoticeRepository implements NoticeRepositoryInterface
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private readonly Resource $resource,
        private readonly ModelFactory $noticeFactory,
        private readonly CollectionFactory $collectionFactory,
        private readonly NoticeSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly StoreManagerInterface $storeManager,
        private readonly NoticeDataMapper $entityDataMapper,
        private readonly CollectionProcessor $collectionProcessor,
        private readonly HydratorInterface $hydrator
    ) {
    }

    /**
     * Save Notification data
     *
     * @param NoticeInterface $notice
     * @return NoticeInterface
     * @throws CouldNotSaveException
     */
    public function save(NoticeInterface $notice): NoticeInterface
    {
        if (empty($notice->getStoreId())) {
            $notice->setStoreId((int) $this->storeManager->getStore()->getId());
        }

        if ($notice->getNoticeId() && $notice instanceof Notice && !$notice->getOrigData()) {
            $notice = $this->hydrator->hydrate($this->getById($notice->getId()), $this->hydrator->extract($notice));
        }

        if (!$notice instanceof Model) {
            throw new CouldNotSaveException(__('Invalid notice instance.'));
        }

        try {
            $this->resource->save($notice);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $notice;
    }

    /**
     * Load Block data by given Block Identity
     *
     * @param string $noticeId
     * @return NoticeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($noticeId): NoticeInterface
    {
        $notice = $this->noticeFactory->create();
        $this->resource->load($notice, $noticeId);
        if (!$notice->getId()) {
            throw new NoSuchEntityException(__('The entity with the "%1" ID doesn\'t exist.', $noticeId));
        }
        return $notice;
    }

    /**
     * Load Block data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $searchCriteria
     * @return NoticeSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $entityDataObjects = $this->entityDataMapper->map($collection);

        /** @var NoticeSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($entityDataObjects);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Block
     *
     * @param NoticeInterface $notice
     * @throws CouldNotDeleteException
     */
    public function delete(NoticeInterface $notice): bool
    {
        if (!$notice instanceof Model) {
            throw new CouldNotDeleteException(__('Invalid notice instance.'));
        }

        try {
            $this->resource->delete($notice);
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
    public function deleteById(string $noticeId): bool
    {
        return $this->delete($this->getById($noticeId));
    }
}
