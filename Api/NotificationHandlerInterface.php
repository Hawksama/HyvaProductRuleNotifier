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
    public const MATCH_TYPE_LESS_THAN = '<';
    public const MATCH_TYPE_GREATER_THAN = '>';
    public const MATCH_TYPE_LESS_THAN_OR_EQUAL = '<=';
    public const MATCH_TYPE_GREATER_THAN_OR_EQUAL = '>=';
    public const MATCH_TYPE_EQUAL = '==';

    /**
     * Retrieve notifications for the given cart items.
     *
     * @return NotificationInterface[]
     */
    public function getNotificationsForCartItems(array $cartItems): array;
}
