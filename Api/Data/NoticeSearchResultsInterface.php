<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use Hawksama\Notice\Api\Data\NoticeInterface;

interface NoticeSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list.
     *
     * @return NoticeInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param NoticeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
