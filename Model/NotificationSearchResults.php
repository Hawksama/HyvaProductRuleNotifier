<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Model;

use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Service Data Object with Notification search results.
 */
class NotificationSearchResults extends SearchResults implements NotificationSearchResultsInterface
{
}
