<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Controller\Check;

use Hawksama\ProductRuleNotifier\Api\Data\NotificationInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Hawksama\ProductRuleNotifier\ViewModel\NotificationViewModel;

class Notifications implements HttpGetActionInterface
{
    public function __construct(
        private readonly JsonFactory $jsonFactory,
        private readonly NotificationViewModel $notificationViewModel
    ) {
    }

    public function execute(): Json
    {
        $result = $this->jsonFactory->create();

        try {
            $notifications = $this->notificationViewModel->getNotifications();
            $notificationData = array_map(
                function (NotificationInterface $notification) {
                    return [
                        'message' => $notification->getDescription()
                    ];
                },
                $notifications
            );
        } catch (\Exception $e) {
            return $result->setData(['error' => __('Unable to fetch notifications.')]);
        }

        return $result->setData(['notifications' => $notificationData]);
    }
}
