<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\ViewModel;

use Hawksama\ProductRuleNotifier\Api\Data\NoticeInterface;
use Hawksama\ProductRuleNotifier\Model\NotificationHandler;
use Hawksama\ProductRuleNotifier\Service\CartItemRetriever;
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
     * Retrieve the list of notices.
     *
     * @return NoticeInterface[]
     * @throws LocalizedException
     */
    public function getNotifications(): array
    {
        $quoteItems = $this->cartItemRetriever->getQuoteItems();

        if (empty($quoteItems)) {
            return [];
        }

        return $this->notificationHandler->getNoticesForCartItems($quoteItems);
    }
}
