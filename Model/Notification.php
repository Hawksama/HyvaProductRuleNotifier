<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Model;

use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationInterface;
use Hawksama\HyvaProductRuleNotifier\Model\ResourceModel\Notification as Resource;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Notification extends AbstractModel implements NotificationInterface, IdentityInterface
{
    public const CACHE_TAG = 'hawksama_notification';
    protected $_eventPrefix = 'hawksama_notification_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Resource::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }

    /**
     * @inheirtdoc
     */
    public function getNotificationId(): ?int
    {
        return $this->getData(self::NOTIFICATION_ID) === null ? null
            : (int)$this->getData(self::NOTIFICATION_ID);
    }

    /**
     * @inheirtdoc
     */
    public function setNotificationId(?int $notificationId): void
    {
        $this->setData(self::NOTIFICATION_ID, $notificationId);
    }

    /**
     * @inheirtdoc
     */
    public function getEnabled(): ?bool
    {
        return $this->getData(self::ENABLED) === null ? null
            : (bool)$this->getData(self::ENABLED);
    }

    /**
     * @inheirtdoc
     */
    public function setEnabled(?bool $enabled): void
    {
        $this->setData(self::ENABLED, $enabled);
    }

    /**
     * @inheirtdoc
     */
    public function getRuleName(): ?string
    {
        return $this->getData(self::RULE_NAME);
    }

    /**
     * @inheirtdoc
     */
    public function setRuleName(?string $ruleName): void
    {
        $this->setData(self::RULE_NAME, $ruleName);
    }

    /**
     * @inheirtdoc
     */
    public function getStoreId(): array|int|null
    {
        return $this->getData(self::STORE_ID) === null ? null
            : $this->getData(self::STORE_ID);
    }

    /**
     * @inheirtdoc
     */
    public function setStoreId(array|int|null $storeId = null): void
    {
        $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @inheirtdoc
     */
    public function getProductAttribute(): ?string
    {
        return $this->getData(self::PRODUCT_ATTRIBUTE);
    }

    /**
     * @inheirtdoc
     */
    public function setProductAttribute(?string $productAttribute): void
    {
        $this->setData(self::PRODUCT_ATTRIBUTE, $productAttribute);
    }

    /**
     * @inheirtdoc
     */
    public function getWord(): string
    {
        return $this->getData(self::WORD);
    }

    /**
     * @inheirtdoc
     */
    public function setWord(string $word): void
    {
        $this->setData(self::WORD, $word);
    }

    /**
     * @inheirtdoc
     */
    public function getMatchType(): string
    {
        return $this->getData(self::MATCH_TYPE);
    }

    /**
     * @inheirtdoc
     */
    public function setMatchType(?string $matchType): void
    {
        $this->setData(self::MATCH_TYPE, $matchType);
    }

    /**
     * @inheirtdoc
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheirtdoc
     */
    public function setDescription(?string $description): void
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function getNotificationType(): ?int
    {
        return $this->getData(self::NOTIFICATION_TYPE) === null ? null
            : (int)$this->getData(self::NOTIFICATION_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setNotificationType(?int $notificationType): void
    {
        $this->setData(self::NOTIFICATION_TYPE, $notificationType);
    }

    /**
     * @inheirtdoc
     */
    public function getScheduleEnabled(): ?bool
    {
        return $this->getData(self::SCHEDULE_ENABLED) === null ? null
            : (bool)$this->getData(self::SCHEDULE_ENABLED);
    }

    /**
     * @inheirtdoc
     */
    public function setScheduleEnabled(?bool $enabled): void
    {
        $this->setData(self::SCHEDULE_ENABLED, $enabled);
    }

    /**
     * @inheirtdoc
     */
    public function getStartDate(): ?string
    {
        return $this->getData(self::START_DATE) === null ? null
            : (string)$this->getData(self::START_DATE);
    }

    /**
     * @inheirtdoc
     */
    public function setStartDate(?string $startDate): void
    {
        $this->setData(self::START_DATE, $startDate);
    }

    /**
     * @inheirtdoc
     */
    public function getEndDate(): ?string
    {
        return $this->getData(self::END_DATE) === null ? null
            : (string)$this->getData(self::END_DATE);
    }

    /**
     * @inheirtdoc
     */
    public function setEndDate(?string $endDate): void
    {
        $this->setData(self::END_DATE, $endDate);
    }

    /**
     * Receive page store ids
     */
    public function getStores(): array
    {
        return $this->getData('store_id');
    }
}
