<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Model;

use Hawksama\Notice\Api\Data\NoticeInterface;
use Hawksama\Notice\Api\NotificationHandlerInterface;
use Hawksama\Notice\Api\NoticeRepositoryInterface;
use Hawksama\Notice\Query\Notice\GetListQuery;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\CartItemInterface;

class NotificationHandler implements NotificationHandlerInterface
{
    public function __construct(
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private NoticeRepositoryInterface $noticeRepository,
    ) {
    }

    /**
     * {@inheritdoc}
     * @param CartItemInterface[] $cartItems
     * @throws LocalizedException
     */
    public function getNoticesForCartItems(array $cartItems): array
    {
        $notices = [];
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->noticeRepository->getList($searchCriteria);

        /** @var NoticeInterface[] $rules */
        $rules = $searchResults->getItems();

        foreach ($rules as $rule) {
            $attributeCode = $rule->getProductAttribute();
            $attributeValue = $rule->getWord();
            $matchType = $rule->getMatchType();

            /** @var QuoteItem $item */
            foreach ($cartItems as $item) {
                if (!empty($attributeCode)) {
                    $productAttributeValue = $item->getProduct()->getData($attributeCode);

                    if ($this->isMatching($productAttributeValue, $attributeValue, $matchType)) {
                        $notices[] = $rule;
                    }
                }
            }
        }

        return array_unique($notices, SORT_REGULAR);
    }

    /**
     * Check if the product attribute value matches the rule value based on the match type.
     */
    private function isMatching(?string $productAttributeValue, string $ruleValue, string $matchType): bool
    {
        if (empty($productAttributeValue)) {
            return false;
        }

        return match ($matchType) {
            self::MATCH_TYPE_EXACT => $productAttributeValue === $ruleValue,
            self::MATCH_TYPE_LIKE => stripos($productAttributeValue, $ruleValue) !== false,
            default => false,
        };
    }
}
