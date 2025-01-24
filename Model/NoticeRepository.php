<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Model;

use Hawksama\Notice\Api\Data\NoticeSearchResultsInterface;
use Hawksama\Notice\Api\NoticeRepositoryInterface;
use Hawksama\Notice\Api\Data\NoticeInterface;
use Hawksama\Notice\Api\Data\NoticeInterfaceFactory;
use Hawksama\Notice\Api\Data\NoticeSearchResultsInterfaceFactory;
use Hawksama\Notice\Model\Notice;
use Hawksama\Notice\Model\NoticeFactory;
use Hawksama\Notice\Model\ResourceModel\Notice as Resource;
use Hawksama\Notice\Model\ResourceModel\Notice\Collection;
use Hawksama\Notice\Model\ResourceModel\Notice\CollectionFactory;
use Hawksama\Notice\Mapper\NoticeDataMapper;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\EntityManager\HydratorInterface;

/**
 * Default block repo impl.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class NoticeRepository implements NoticeRepositoryInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var HydratorInterface
     */
    private mixed $hydrator;

    /**
     * @param Resource $resource
     * @param NoticeFactory $noticeFactory
     * @param NoticeInterfaceFactory $dataNoticeFactory
     * @param CollectionFactory $collectionFactory
     * @param NoticeSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param HydratorInterface|null $hydrator
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private readonly Resource $resource,
        private readonly NoticeFactory $noticeFactory,
        private readonly NoticeInterfaceFactory $dataNoticeFactory,
        private readonly CollectionFactory $collectionFactory,
        private readonly NoticeSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly DataObjectHelper $dataObjectHelper,
        private readonly DataObjectProcessor $dataObjectProcessor,
        private readonly StoreManagerInterface $storeManager,
        private readonly NoticeDataMapper $entityDataMapper,
        CollectionProcessorInterface $collectionProcessor = null,
        ?HydratorInterface $hydrator = null
    ) {
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
        $this->hydrator = $hydrator ?? ObjectManager::getInstance()->get(HydratorInterface::class);
    }

    /**
     * Save Notice data
     *
     * @param NoticeInterface $notice
     * @return Notice
     * @throws CouldNotSaveException
     */
    public function save(NoticeInterface $notice)
    {
        if (empty($notice->getStoreId())) {
            $notice->setStoreId((int) $this->storeManager->getStore()->getId());
        }

        if ($notice->getNoticeId() && $notice instanceof Notice && !$notice->getOrigData()) {
            $notice = $this->hydrator->hydrate($this->getById($notice->getId()), $this->hydrator->extract($notice));
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
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(NoticeInterface $notice)
    {
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
     * @param string $noticeId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(string $noticeId): bool
    {
        return $this->delete($this->getById($noticeId));
    }

    /**
     * Retrieve collection processor
     *
     * @deprecated 102.0.0
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor(): CollectionProcessorInterface
    {
        //phpcs:disable Magento2.PHP.LiteralNamespaces
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Hawksama\Notice\Model\Api\SearchCriteria\NoticeCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}
