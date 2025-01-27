<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Api;

use Hawksama\ProductRuleNotifier\Api\Data\NotificationInterface;
use Hawksama\ProductRuleNotifier\Api\Data\NotificationSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * CMS block CRUD interface.
 *
 * @api
 * @since 100.0.2
 */
interface NotificationRepositoryInterface
{
    /**
     * Save block.
     *
     * @param NotificationInterface $notification
     * @return NotificationInterface
     * @throws LocalizedException
     */
    public function save(Data\NotificationInterface $notification);

    /**
     * Retrieve block.
     *
     * @param string $notificationId
     * @return NotificationInterface
     * @throws LocalizedException
     */
    public function getById($notificationId);

    /**
     * Retrieve blocks matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return NotificationSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete block.
     *
     * @param NotificationInterface $notification
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(Data\NotificationInterface $notification);

    /**
     * Delete block by ID.
     *
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(string $notificationId);
}
