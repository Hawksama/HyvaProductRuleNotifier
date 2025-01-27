<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Mapper;

use Hawksama\ProductRuleNotifier\Api\Data\NotificationInterface;
use Hawksama\ProductRuleNotifier\Api\Data\NotificationInterfaceFactory as ApiDataInterfaceFactory;
use Hawksama\ProductRuleNotifier\Model\Notification as Model;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Converts a collection of Notification entities to an array of data transfer objects.
 */
class NotificationDataMapper
{
    public function __construct(
        private readonly ApiDataInterfaceFactory $entityDtoFactory
    ) {
    }

    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var Model $item */
        foreach ($collection->getItems() as $item) {
            /** @var Model $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->setData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
