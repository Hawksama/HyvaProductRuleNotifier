<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Model;

use Hawksama\Notice\Api\Data\NoticeInterface;
use Hawksama\Notice\Api\NoticeHandlerInterface;
use Hawksama\Notice\Api\NoticeRepositoryInterface;
use Hawksama\Notice\Query\Notice\GetListQuery;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\CartItemInterface;

class NoticeHandler implements NoticeHandlerInterface
{
    public function __construct(
        private readonly GetListQuery $getListQuery,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private NoticeRepositoryInterface $noticeRepository,
    ) {
    }

    /**
     * {@inheritdoc}
     * @throws LocalizedException
     */
    public function getNoticesForCartItems(array $cartItems): array
    {
        $notices = [];
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->noticeRepository->getList($searchCriteria);

        $rules = $searchResults->getItems();

        /** @var NoticeInterface $rule */
        foreach ($rules as $rule) {
            $attributeCode = $rule->getProductAttribute();
            $attributeValue = $rule->getWord();
            $matchType = $rule->getMatchType();

            foreach ($cartItems as $item) {
                $productAttributeValue = $item->getProduct()->getData($attributeCode);

                if ($this->isMatching($productAttributeValue, $attributeValue, $matchType)) {
                    $notices[] = $rule->getDescription();
                }
            }
        }

        return array_filter(array_unique($notices));
    }

    /**
     * Check if the product attribute value matches the rule value based on the match type.
     */
    private function isMatching(?string $productAttributeValue, string $ruleValue, string $matchType): bool
    {
        if ($productAttributeValue === null || $productAttributeValue === '') {
            return false;
        }

        return match ($matchType) {
            NoticeHandlerInterface::MATCH_TYPE_EXACT => $productAttributeValue === $ruleValue,
            NoticeHandlerInterface::MATCH_TYPE_LIKE => stripos($productAttributeValue, $ruleValue) !== false,
            default => false,
        };
    }
}
