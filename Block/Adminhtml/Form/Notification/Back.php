<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Block\Adminhtml\Form\Notification;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Back to list button.
 */
class Back extends GenericButton implements ButtonProviderInterface
{
    /**
     * Retrieve Back To Grid button settings.
     */
    public function getButtonData(): array
    {
        return $this->wrapButtonSettings(
            __('Back To Grid')->getText(),
            'back',
            sprintf("location.href = '%s';", $this->getUrl('*/*/')),
            [],
            10
        );
    }
}
