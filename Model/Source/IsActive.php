<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Model\Source;

use Hawksama\HyvaProductRuleNotifier\Model\Notification as Model;
use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{
    public function __construct(
        protected Model $notification
    ) {
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->notification->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
