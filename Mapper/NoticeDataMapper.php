<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Mapper;

use Hawksama\Notice\Api\Data\NoticeInterfaceFactory;
use Hawksama\Notice\Model\Notice as Model;
use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Converts a collection of Notification entities to an array of data transfer objects.
 */
class NoticeDataMapper
{
    public function __construct(
        private readonly NoticeInterfaceFactory $entityDtoFactory
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
