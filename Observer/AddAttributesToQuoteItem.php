<?php

/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

declare(strict_types=1);

namespace Hawksama\Notice\Observer;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Hawksama\Notice\Api\Data\NoticeInterface;
use Hawksama\Notice\Query\Notice\GetListQuery;

class AddAttributesToQuoteItem implements ObserverInterface
{
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        private EavConfig $eavConfig,
        private readonly GetListQuery $getListQuery,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    public function execute(Observer $observer): void
    {
        /** @var QuoteItem $quoteItem */
        $quoteItem = $observer->getEvent()->getQuoteItem();
        $productId = $quoteItem->getProductId();
        $quoteProduct = $observer->getEvent()->getProduct();

        $product = $this->productRepository->getById($productId);

        $rules = $this->getActiveNoticeRules();

        foreach ($rules as $rule) {
            $attributeCode = $rule->getProductAttribute();
            $attributeValue = $this->getProductAttributeValue($product, $attributeCode);

            if ($attributeValue !== null) {
                $quoteProduct->setData($attributeCode, $attributeValue);
            }
        }
        $t = 2;
    }

    private function getActiveNoticeRules(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('enabled', true)->create();
        $searchResults = $this->getListQuery->execute($searchCriteria);

        return $searchResults->getItems();
    }

    private function getProductAttributeValue($product, string $attributeCode): ?string
    {
        $value = $product->getData($attributeCode);

        if (!empty($value)) {
            $attribute = $product->getResource()->getAttribute($attributeCode);
            if ($attribute && ($attribute->getFrontendInput() === 'select' || $attribute->getFrontendInput() === 'multiselect')) {
                return $attribute->getSource()->getOptionText($value);
            }
        }

        return $value !== null ? (string)$value : null;
    }
}
