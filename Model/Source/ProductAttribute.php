<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Model\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class ProductAttribute implements OptionSourceInterface
{
    /**
     * Constructor
     *
     * @param AttributeCollectionFactory $attributeCollectionFactory
     */
    public function __construct(
        private readonly AttributeCollectionFactory $attributeCollectionFactory
    ) {
    }

    /**
     * Retrieve option array for form field
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [];

        // Load product attributes
        $attributeCollection = $this->attributeCollectionFactory->create()
            ->addFieldToFilter('entity_type_id', 4) // 4 = Product entity type
            ->addFieldToFilter('is_user_defined', 1);

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

    /**
     * @param $attribute
     *
     * @return mixed
     */
    private function getLabel($attribute)
    {
        $attrFrontendLabel = str_replace("'", '', $attribute->getFrontendLabel() ?? '');
        return $attribute->getAttributeCode() . ' - ' . $attrFrontendLabel;
    }
}
