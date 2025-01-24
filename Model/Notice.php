<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Model;

use Hawksama\Notice\Api\Data\NoticeInterface;
use Hawksama\Notice\Model\ResourceModel\Notice as Resource;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Notice extends AbstractModel implements NoticeInterface, IdentityInterface
{
    public const CACHE_TAG = 'hawksama_notice';
	protected $_eventPrefix = 'hawksama_notice_model';
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;

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
     * Getter for NoticeId.
     *
     * @return int|null
     */
    public function getNoticeId(): ?int
    {
        return $this->getData(self::NOTICE_ID) === null ? null
            : (int)$this->getData(self::NOTICE_ID);
    }

    /**
     * Setter for NoticeId.
     *
     * @param int|null $noticeId
     * @return void
     */
    public function setNoticeId(?int $noticeId): void
    {
        $this->setData(self::NOTICE_ID, $noticeId);
    }

    /**
     * Getter for Enabled.
     *
     * @return bool|null
     */
    public function getEnabled(): ?bool
    {
        return $this->getData(self::ENABLED) === null ? null
            : (bool)$this->getData(self::ENABLED);
    }

    /**
     * Setter for Enabled.
     *
     * @param bool|null $enabled
     * @return void
     */
    public function setEnabled(?bool $enabled): void
    {
        $this->setData(self::ENABLED, $enabled);
    }

    /**
     * Getter for RuleName.
     *
     * @return string|null
     */
    public function getRuleName(): ?string
    {
        return $this->getData(self::RULE_NAME);
    }

    /**
     * Setter for RuleName.
     *
     * @param string|null $ruleName
     * @return void
     */
    public function setRuleName(?string $ruleName): void
    {
        $this->setData(self::RULE_NAME, $ruleName);
    }

    /**
     * Getter for StoreId.
     *
     * @return int|null
     */
    public function getStoreId(): ?int
    {
        return $this->getData(self::STORE_ID) === null ? null
            : (int)$this->getData(self::STORE_ID);
    }

    /**
     * Setter for StoreId.
     *
     * @param int|null $storeId
     * @return void
     */
    public function setStoreId(?int $storeId): void
    {
        $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Getter for ProductAttribute.
     *
     * @return string|null
     */
    public function getProductAttribute(): ?string
    {
        return $this->getData(self::PRODUCT_ATTRIBUTE);
    }

    /**
     * Setter for ProductAttribute.
     *
     * @param string|null $productAttribute
     * @return void
     */
    public function setProductAttribute(?string $productAttribute): void
    {
        $this->setData(self::PRODUCT_ATTRIBUTE, $productAttribute);
    }

    /**
     * Getter for Word.
     *
     * @return string|null
     */
    public function getWord(): ?string
    {
        return $this->getData(self::WORD);
    }

    /**
     * Setter for Word.
     *
     * @param string|null $word
     * @return void
     */
    public function setWord(?string $word): void
    {
        $this->setData(self::WORD, $word);
    }

    /**
     * Getter for MatchType.
     *
     * @return string|null
     */
    public function getMatchType(): ?string
    {
        return $this->getData(self::MATCH_TYPE);
    }

    /**
     * Setter for MatchType.
     *
     * @param string|null $matchType
     * @return void
     */
    public function setMatchType(?string $matchType): void
    {
        $this->setData(self::MATCH_TYPE, $matchType);
    }

    /**
     * Getter for Description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Setter for Description.
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Getter for ScheduleEnabled.
     *
     * @return bool|null
     */
    public function getScheduleEnabled(): ?bool
    {
        return $this->getData(self::SCHEDULE_ENABLED) === null ? null
            : (bool)$this->getData(self::SCHEDULE_ENABLED);
    }

    /**
     * Setter for ScheduleEnabled.
     *
     * @param bool|null $enabled
     * @return void
     */
    public function setScheduleEnabled(?bool $enabled): void
    {
        $this->setData(self::SCHEDULE_ENABLED, $enabled);
    }

    /**
     * Getter for StartDate.
     *
     * @return int|null
     */
    public function getStartDate(): ?int
    {
        return $this->getData(self::START_DATE) === null ? null
            : (int)$this->getData(self::START_DATE);
    }

    /**
     * Setter for StartDate.
     *
     * @param int|null $startDate
     * @return void
     */
    public function setStartDate(?int $startDate): void
    {
        $this->setData(self::START_DATE, $startDate);
    }

    /**
     * Getter for EndDate.
     *
     * @return int|null
     */
    public function getEndDate(): ?int
    {
        return $this->getData(self::END_DATE) === null ? null
            : (int)$this->getData(self::END_DATE);
    }

    /**
     * Setter for EndDate.
     *
     * @param int|null $endDate
     * @return void
     */
    public function setEndDate(?int $endDate): void
    {
        $this->setData(self::END_DATE, $endDate);
    }

    /**
     * Receive page store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    /**
     * Prepare block's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}
