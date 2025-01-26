<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Controller\Adminhtml\Notification;

use Hawksama\ProductRuleNotifier\Api\Data\NoticeInterface;
use Hawksama\ProductRuleNotifier\Model\NoticeFactory as ModelFactory;
use Hawksama\ProductRuleNotifier\Model\ResourceModel\Notification as Resource;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

// phpcs:disable Generic.Files.LineLength.TooLong
class Delete extends \Hawksama\ProductRuleNotifier\Controller\Adminhtml\Notice implements HttpPostActionInterface, HttpGetActionInterface
{
    public function __construct(
        Context $context,
        private readonly ModelFactory $modelFactory,
        private readonly Resource $resource,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');
        $entityId = $this->getRequest()->getParam(NoticeInterface::NOTICE_ID);

        try {
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, NoticeInterface::NOTICE_ID);

            if (!$model->getId()) {
                throw new NoSuchEntityException(
                    __('Could not find Notification with id: %1', $entityId)
                );
            }

            $this->resource->delete($model);
            $this->messageManager->addSuccessMessage(
                (string) __('You have successfully deleted the notification.')
            );
        } catch (NoSuchEntityException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        } catch (\Exception $exception) {
            $this->logger->error(
                __('Could not delete this notification. Original message: %1', $exception->getMessage()),
                ['exception' => $exception]
            );
            $this->messageManager->addErrorMessage(
                (string) __('An error occurred while deleting the notification.')
            );
        }

        return $resultRedirect;
    }
}
