<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\ViewModel;

use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationInterface;
use Hawksama\HyvaProductRuleNotifier\Model\NotificationHandler;
use Hawksama\HyvaProductRuleNotifier\Service\CartItemRetriever;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class NotificationViewModel implements ArgumentInterface
{
    public function __construct(
        private readonly NotificationHandler $notificationHandler,
        private readonly CartItemRetriever $cartItemRetriever
    ) {
    }

    /**
     * Retrieve the list of notifications.
     *
     * @return NotificationInterface[]
     * @throws LocalizedException
     */
    public function getNotifications(): array
    {
        $quoteItems = $this->cartItemRetriever->getQuoteItems();

        if (empty($quoteItems)) {
            return [];
        }

        return $this->notificationHandler->getNotificationsForCartItems($quoteItems);
    }
}
