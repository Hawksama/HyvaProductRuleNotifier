<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Ui\Component\Listing\Column;

use Hawksama\ProductRuleNotifier\Api\Data\NotificationInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class to build edit and delete link for each item.
 */
class NotificationBlockActions extends Column
{
    private const ENTITY_NAME = 'Notification';
    private const EDIT_URL_PATH = 'hawksama/notification/edit';
    private const DELETE_URL_PATH = 'hawksama/notification/delete';

    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        private readonly UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
    }

    /**
     * Prepare data source.
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[NotificationInterface::NOTIFICATION_ID])) {
                    $entityName = self::ENTITY_NAME;
                    $urlData = [
                        NotificationInterface::NOTIFICATION_ID => $item[NotificationInterface::NOTIFICATION_ID]
                    ];

                    $editUrl = $this->urlBuilder->getUrl(self::EDIT_URL_PATH, $urlData);
                    $deleteUrl = $this->urlBuilder->getUrl(self::DELETE_URL_PATH, $urlData);

                    $item[$this->getData('name')] = [
                        'edit' => $this->getActionData($editUrl, (string)__('Edit')),
                        'delete' => $this->getActionData(
                            $deleteUrl,
                            (string)__('Delete'),
                            (string)__('Delete %1', $entityName),
                            (string)__('Are you sure you want to delete a %1 record?', $entityName)
                        )
                    ];
                }
            }
        }

        return $dataSource;
    }

    /**
     * Get action link data array.
     */
    private function getActionData(
        string  $url,
        string  $label,
        ?string $dialogTitle = null,
        ?string $dialogMessage = null
    ): array {
        $data = [
            'href' => $url,
            'label' => $label,
            'post' => true,
            '__disableTmpl' => true
        ];

        if ($dialogTitle && $dialogMessage) {
            $data['confirm'] = [
                'title' => $dialogTitle,
                'message' => $dialogMessage
            ];
        }

        return $data;
    }
}
