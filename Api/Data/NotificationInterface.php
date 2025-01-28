<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Api\Data;

interface NotificationInterface
{
    /**
     * String constants for property names
     */
    public const NOTIFICATION_ID = "notification_id";
    public const ENABLED = "enabled";
    public const RULE_NAME = "rule_name";
    public const STORE_ID = "store_id";
    public const PRODUCT_ATTRIBUTE = "product_attribute";
    public const VALUE = "value";
    public const MATCH_TYPE = "match_type";
    public const DESCRIPTION = "description";
    public const SCHEDULE_ENABLED = "schedule_enabled";
    public const START_DATE = "start_date";
    public const END_DATE = "end_date";
    public const NOTIFICATION_TYPE = "notification_type";

    /**
     * Getter for NotificationId.
     *
     * @return int|null
     */
    public function getNotificationId(): ?int;

    /**
     * Setter for NotificationId.
     *
     * @param int|null $notificationId
     * @return void
     */
    public function setNotificationId(?int $notificationId): void;

    /**
     * Getter for Enabled.
     *
     * @return bool|null
     */
    public function getEnabled(): ?bool;

    /**
     * Setter for Enabled.
     *
     * @param bool|null $enabled
     * @return void
     */
    public function setEnabled(?bool $enabled): void;

    /**
     * Getter for RuleName.
     *
     * @return string|null
     */
    public function getRuleName(): ?string;

    /**
     * Setter for RuleName.
     *
     * @param string|null $ruleName
     * @return void
     */
    public function setRuleName(?string $ruleName): void;

    /**
     * Getter for StoreId.
     *
     * @return array|int|null
     */
    public function getStoreId(): array|int|null;

    /**
     * Setter for StoreId.
     *
     * @param array|int|null $storeId
     * @return void
     */
    public function setStoreId(array|int|null $storeId = null): void;

    /**
     * Getter for ProductAttribute.
     *
     * @return string|null
     */
    public function getProductAttribute(): ?string;

    /**
     * Setter for ProductAttribute.
     *
     * @param string|null $productAttribute
     * @return void
     */
    public function setProductAttribute(?string $productAttribute): void;

    /**
     * Getter for Value.
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Setter for Value.
     *
     * @param string $value
     * @return void
     */
    public function setValue(string $value): void;

    /**
     * Getter for MatchType.
     *
     * @return string
     */
    public function getMatchType(): string;

    /**
     * Setter for MatchType.
     *
     * @param string $matchType
     * @return void
     */
    public function setMatchType(string $matchType): void;

    /**
     * Getter for Description.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Setter for Description.
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void;

    /**
     * Getter for NotificationType.
     *
     * @return int|null
     */
    public function getNotificationType(): ?int;

    /**
     * Setter for NotificationType.
     *
     * @param int|null $notificationType
     * @return void
     */
    public function setNotificationType(?int $notificationType): void;

    /**
     * Getter for ScheduleEnabled.
     *
     * @return bool|null
     */
    public function getScheduleEnabled(): ?bool;

    /**
     * Setter for ScheduleEnabled.
     *
     * @param bool|null $enabled
     * @return void
     */
    public function setScheduleEnabled(?bool $enabled): void;

    /**
     * Getter for StartDate.
     *
     * @return string|null
     */
    public function getStartDate(): ?string;

    /**
     * Setter for StartDate.
     *
     * @param string|null $startDate
     * @return void
     */
    public function setStartDate(?string $startDate): void;

    /**
     * Getter for EndDate.
     *
     * @return string|null
     */
    public function getEndDate(): ?string;

    /**
     * Setter for EndDate.
     *
     * @param string|null $endDate
     * @return void
     */
    public function setEndDate(?string $endDate): void;
}
