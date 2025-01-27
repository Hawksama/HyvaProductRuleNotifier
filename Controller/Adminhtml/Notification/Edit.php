<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Controller\Adminhtml\Notification;

use Hawksama\HyvaProductRuleNotifier\Controller\Adminhtml\Controller;
use Hawksama\HyvaProductRuleNotifier\Model\NotificationFactory as ModelFactory;
use Hawksama\HyvaProductRuleNotifier\Model\NotificationRepository as ModelRepository;
use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationInterface;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

/**
 * Edit Notification entity backend controller.
 */
class Edit extends Controller implements HttpGetActionInterface
{
    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory,
        private readonly ModelFactory $notificationFactory,
        private readonly ModelRepository $repository
    ) {
        parent::__construct($context);
    }

    /**
     * @throws NoSuchEntityException
     */
    public function execute(): Page|ResultInterface
    {
        /** @var NotificationInterface $model */
        $model = $this->notificationFactory->create();
        $id = $this->getRequest()->getParam(NotificationInterface::NOTIFICATION_ID);

        if ($id) {
            $model = $this->repository->getById($id);
            if (!$model->getNotificationId()) {
                $this->messageManager->addErrorMessage(__('This entity no longer exists.')->render());
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage = $this->initPage($resultPage);
        $resultPage->getConfig()->getTitle()->prepend(__('Notifications')->getText());
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getNotificationId() ?
                (string) $model->getRuleName() :
                __('New Product Rule Notification')->render()
        );
        return $resultPage;
    }
}
