<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Api;

use Hawksama\ProductRuleNotifier\Api\Data\NoticeInterface;
use Hawksama\ProductRuleNotifier\Api\Data\NoticeSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * CMS block CRUD interface.
 *
 * @api
 * @since 100.0.2
 */
interface NoticeRepositoryInterface
{
    /**
     * Save block.
     *
     * @param NoticeInterface $notice
     * @return NoticeInterface
     * @throws LocalizedException
     */
    public function save(Data\NoticeInterface $notice);

    /**
     * Retrieve block.
     *
     * @param string $noticeId
     * @return NoticeInterface
     * @throws LocalizedException
     */
    public function getById($noticeId);

    /**
     * Retrieve blocks matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return NoticeSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete block.
     *
     * @param NoticeInterface $notice
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(Data\NoticeInterface $notice);

    /**
     * Delete block by ID.
     *
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(string $noticeId);
}
