<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Controller\Adminhtml\Notification;

use Hawksama\ProductRuleNotifier\Api\Data\NotificationInterface;
use Hawksama\ProductRuleNotifier\Controller\Adminhtml\Controller;
use Hawksama\ProductRuleNotifier\Model\Notification as Model;
use Hawksama\ProductRuleNotifier\Model\NotificationFactory as ModelFactory;
use Hawksama\ProductRuleNotifier\Model\NotificationRepository as ModelRepository;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save Notification controller action.
 */
class Save extends Controller implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly ModelFactory $notificationFactory,
        private readonly ModelRepository $repository
    ) {
        parent::__construct($context);
    }

    /**
     * Save Notification Action.
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /** @var \Magento\Framework\HTTP\PhpEnvironment\Request $request */
        $request = $this->getRequest();

        /** @var array $data */
        $data = $request->getPostValue();

        if ($data) {
            /** @var Model $model */
            $model = $this->notificationFactory->create();

            $id = $this->getRequest()->getParam('notification_id');
            if ($id) {
                try {
                    /** @var Model $model */
                    $model = $this->repository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage((string) __('This notification no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->repository->save($model);
                $this->messageManager->addSuccessMessage(
                    (string) __('The notification was saved successfully')
                );
                $this->dataPersistor->clear('hawksama_notification_data');
            } catch (CouldNotSaveException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                $this->dataPersistor->set('hawksama_notification_data', $data);
            }

            return $resultRedirect->setPath('*/*/edit', [
                NotificationInterface::NOTIFICATION_ID => $model->getNotificationId(),
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
