<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Service;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item;

class CartItemRetriever
{
    public function __construct(
        private readonly CheckoutSession $checkoutSession
    ) {
    }

    /**
     * Retrieve all visible items from the current quote.
     *
     * @return Item[]
     * @throws LocalizedException
     */
    public function getQuoteItems(): array
    {
        try {
            $quote = $this->checkoutSession->getQuote();
        } catch (NoSuchEntityException | LocalizedException $e) {
            throw new LocalizedException(__('Unable to retrieve quote items.'));
        }

        return $quote->getAllVisibleItems();
    }
}
