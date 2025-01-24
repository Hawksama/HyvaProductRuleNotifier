<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Api;

use Hawksama\Notice\Api\Data\NoticeInterface;
use Magento\Quote\Api\Data\CartItemInterface;

interface NoticeHandlerInterface
{
    public const MATCH_TYPE_EXACT = 'exact';
    public const MATCH_TYPE_LIKE = 'like';

    /**
     * Retrieve notices for the given cart items.
     *
     * @param CartItemInterface[] $cartItems
     * @return NoticeInterface[]
     */
    public function getNoticesForCartItems(array $cartItems): array;
}
