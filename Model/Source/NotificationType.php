<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class NotificationType implements OptionSourceInterface
{
    public const TYPE_ERROR = 0;
    public const TYPE_WARNING = 1;
    public const TYPE_INFORMATION = 2;
    public const TYPE_SUCCESS = 3;

    /**
     * Get options
     */
    public function toOptionArray(): array
    {
        $availableOptions = $this->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }

    /**
     * Prepare block's statuses.
     */
    private function getAvailableStatuses(): array
    {
        return [
            self::TYPE_ERROR => __('Error'),
            self::TYPE_WARNING => __('Warning'),
            self::TYPE_INFORMATION => __('Information'),
            self::TYPE_SUCCESS => __('Success')
        ];
    }
}
