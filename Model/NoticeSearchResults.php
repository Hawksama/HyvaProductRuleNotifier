<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Model;

use Hawksama\ProductRuleNotifier\Api\Data\NoticeSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Service Data Object with Notification search results.
 */
class NoticeSearchResults extends SearchResults implements NoticeSearchResultsInterface
{
}
