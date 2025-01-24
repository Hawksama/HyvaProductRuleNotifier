<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Api\Data;

interface NoticeInterface
{
    /**
     * String constants for property names
     */
    public const NOTICE_ID = "notice_id";
    public const ENABLED = "enabled";
    public const RULE_NAME = "rule_name";
    public const STORE_ID = "store_id";
    public const PRODUCT_ATTRIBUTE = "product_attribute";
    public const WORD = "word";
    public const MATCH_TYPE = "match_type";
    public const DESCRIPTION = "description";
    public const SCHEDULE_ENABLED = "schedule_enabled";
    public const START_DATE = "start_date";
    public const END_DATE = "end_date";

    /**
     * Getter for NoticeId.
     *
     * @return int|null
     */
    public function getNoticeId(): ?int;

    /**
     * Setter for NoticeId.
     *
     * @param int|null $noticeId
     * @return void
     */
    public function setNoticeId(?int $noticeId): void;

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
     * @return int|null
     */
    public function getStoreId(): ?int;

    /**
     * Setter for StoreId.
     *
     * @param int|null $storeId
     * @return void
     */
    public function setStoreId(?int $storeId): void;

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
     * Getter for Word.
     *
     * @return string|null
     */
    public function getWord(): ?string;

    /**
     * Setter for Word.
     *
     * @param string|null $word
     * @return void
     */
    public function setWord(?string $word): void;

    /**
     * Getter for MatchType.
     *
     * @return string|null
     */
    public function getMatchType(): ?string;

    /**
     * Setter for MatchType.
     *
     * @param string|null $matchType
     * @return void
     */
    public function setMatchType(?string $matchType): void;

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
     * @return int|null
     */
    public function getStartDate(): ?int;

    /**
     * Setter for StartDate.
     *
     * @param int|null $startDate
     * @return void
     */
    public function setStartDate(?int $startDate): void;

    /**
     * Getter for EndDate.
     *
     * @return int|null
     */
    public function getEndDate(): ?int;

    /**
     * Setter for EndDate.
     *
     * @param int|null $endDate
     * @return void
     */
    public function setEndDate(?int $endDate): void;
}
