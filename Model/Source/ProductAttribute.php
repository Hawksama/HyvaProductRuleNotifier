<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Model\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Catalog\Setup\CategorySetup;

class ProductAttribute implements OptionSourceInterface
{
    public function __construct(
        private readonly AttributeCollectionFactory $attributeCollectionFactory
    ) {
    }

    public function toOptionArray(): array
    {
        $options = [];

        $attributeCollection = $this->attributeCollectionFactory->create()
            ->addFieldToFilter('entity_type_id', ['eq' => CategorySetup::CATALOG_PRODUCT_ENTITY_TYPE_ID])
            ->addFieldToFilter('is_user_defined', ['eq' => 1]);

        foreach ($attributeCollection as $attribute) {
            $options[] = [
                'value' => $attribute->getAttributeCode(),
                'label' => $this->getLabel($attribute)
            ];
        }

        usort($options, function ($a, $b) {
            return strcmp($a["label"], $b["label"]);
        });

        return $options;
    }

    private function getLabel(Attribute $attribute): string
    {
        $attrFrontendLabel = str_replace("'", '', $attribute->getFrontendLabel() ?? '');
        return $attribute->getAttributeCode() . ' - ' . $attrFrontendLabel;
    }
}
