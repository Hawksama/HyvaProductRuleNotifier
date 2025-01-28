<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class MatchType implements OptionSourceInterface
{
    /**
     * Retrieve match type options
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'exact', 'label' => __('Exact Match')],
            ['value' => 'like', 'label' => __('Match Like')],
            ['value' => '<', 'label' => __('Less Than')],
            ['value' => '<=', 'label' => __('Less Than or Equal')],
            ['value' => '>', 'label' => __('Greater Than')],
            ['value' => '>=', 'label' => __('Greater Than or Equal')],
            ['value' => '==', 'label' => __('Equal')],
        ];
    }
}
