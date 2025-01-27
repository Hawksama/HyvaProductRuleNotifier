<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationInterface;

interface NotificationSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list.
     *
     * @return NotificationInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param NotificationInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
