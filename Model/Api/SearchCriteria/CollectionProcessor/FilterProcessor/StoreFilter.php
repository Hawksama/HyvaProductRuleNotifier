<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor;

use Hawksama\HyvaProductRuleNotifier\Model\ResourceModel\Notification\Collection;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;

class StoreFilter implements CustomFilterInterface
{
    /**
     * Apply custom store filter to collection
     *
     * @param Filter $filter
     * @param AbstractDb $collection
     * @return bool
     */
    public function apply(Filter $filter, AbstractDb $collection)
    {
        /** @var Collection $collection */
        $value = $filter->getValue();
        $storeIds = array_map('intval', explode(',', (string) $value));

        $collection->addStoreFilter($storeIds, false);

        return true;
    }
}
