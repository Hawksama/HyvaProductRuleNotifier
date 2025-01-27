<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Block\Adminhtml\Form\Notification;

use Hawksama\ProductRuleNotifier\Api\Data\NotificationInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;

/**
 * Generic (form) button for Notification entity.
 */
class GenericButton
{
    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @param Context $context
     */
    public function __construct(
        private readonly Context $context
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
    }

    /**
     * Get Notification entity id.
     */
    public function getNotificationId(): int
    {
        return (int)$this->context->getRequest()->getParam(NotificationInterface::NOTIFICATION_ID);
    }

    /**
     * Wrap button specific options to settings array.
     */
    protected function wrapButtonSettings(
        string $label,
        string $class,
        string $onclick = '',
        array  $dataAttribute = [],
        int    $sortOrder = 0
    ): array {
        return [
            'label' => $label,
            'on_click' => $onclick,
            'data_attribute' => $dataAttribute,
            'class' => $class,
            'sort_order' => $sortOrder
        ];
    }

    /**
     * Get url.
     */
    protected function getUrl(string $route, array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
