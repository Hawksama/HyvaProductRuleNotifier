<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Api;

use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationInterface;
use Magento\Quote\Api\Data\CartItemInterface;

interface NotificationHandlerInterface
{
    public const MATCH_TYPE_EXACT = 'exact';
    public const MATCH_TYPE_LIKE = 'like';

    /**
     * Retrieve notifications for the given cart items.
     *
     * @return NotificationInterface[]
     */
    public function getNotificationsForCartItems(array $cartItems): array;
}
