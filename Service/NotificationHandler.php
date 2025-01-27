<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Service;

use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationInterface;
use Hawksama\HyvaProductRuleNotifier\Api\NotificationHandlerInterface;
use Hawksama\HyvaProductRuleNotifier\Api\NotificationRepositoryInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use DateTime;

class NotificationHandler implements NotificationHandlerInterface
{
    public function __construct(
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly NotificationRepositoryInterface $notificationRepository,
        private readonly TimezoneInterface $timezone
    ) {
    }

    /**
     * @inheritdoc
     * @param CartItemInterface[] $cartItems
     * @throws LocalizedException
     */
    public function getNotificationsForCartItems(array $cartItems): array
    {
        $notifications   = [];
        $searchCriteria  = $this->searchCriteriaBuilder->create();
        $searchResults   = $this->notificationRepository->getList($searchCriteria);
        /** @var NotificationInterface[] $rules */
        $rules = $searchResults->getItems();

        foreach ($rules as $rule) {
            // Skip if we're outside of the configured schedule window
            if (!$this->isWithinSchedule($rule)) {
                continue;
            }

            $attributeCode  = $rule->getProductAttribute();
            $attributeValue = $rule->getWord();
            $matchType = $rule->getMatchType();

            /** @var QuoteItem $item */
            foreach ($cartItems as $item) {
                if (!empty($attributeCode)) {
                    $productAttributeValue = $item->getProduct()->getData($attributeCode);

                    if ($this->isMatching($productAttributeValue, $attributeValue, $matchType)) {
                        $notifications[] = $rule;
                    }
                }
            }
        }

        // Remove duplicates
        return array_unique($notifications, SORT_REGULAR);
    }

    /**
     * Checks if the current time falls within the notification's scheduled window.
     *
     * @throws \DateMalformedStringException
     */
    private function isWithinSchedule(NotificationInterface $rule): bool
    {
        if (!$rule->getScheduleEnabled()) {
            // If scheduling is off, always show
            return true;
        }

        $now = $this->timezone->scopeTimeStamp();

        $start = $rule->getStartDate();
        $end = $rule->getEndDate();

        // Convert start and end dates to Unix timestamps
        $startTimestamp = $start ? $this->timezone->date(new DateTime($start))->getTimestamp() : null;
        $endTimestamp = $end ? $this->timezone->date(new DateTime($end))->getTimestamp() : null;

        // If a start date is set and we're before it, skip
        if ($startTimestamp !== null && $now < $startTimestamp) {
            return false;
        }

        // If an end date is set and we're after it, skip
        if ($endTimestamp !== null && $now > $endTimestamp) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the product attribute value matches the rule value based on match type.
     */
    private function isMatching(?string $productAttributeValue, string $ruleValue, string $matchType): bool
    {
        if (empty($productAttributeValue)) {
            return false;
        }

        return match ($matchType) {
            self::MATCH_TYPE_EXACT => $productAttributeValue === $ruleValue,
            self::MATCH_TYPE_LIKE  => stripos($productAttributeValue, $ruleValue) !== false,
            default => false,
        };
    }
}
