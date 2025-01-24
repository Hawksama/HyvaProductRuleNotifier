<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\ViewModel;

use Hawksama\Notice\Api\Data\NoticeInterface;
use Hawksama\Notice\Model\NoticeHandler;
use Hawksama\Notice\Service\CartItemRetriever;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class NoticeViewModel implements ArgumentInterface
{
    public function __construct(
        private readonly NoticeHandler $noticeHandler,
        private readonly CartItemRetriever $cartItemRetriever
    ) {
    }

    /**
     * Retrieve the list of notices.
     *
     * @return NoticeInterface[]
     * @throws LocalizedException
     */
    public function getNotices(): array
    {
        $quoteItems = $this->cartItemRetriever->getQuoteItems();

        if (empty($quoteItems)) {
            return [];
        }

        return $this->noticeHandler->getNoticesForCartItems($quoteItems);
    }
}
