<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Controller\Adminhtml\Notification;

use Hawksama\ProductRuleNotifier\Model\NoticeFactory as ModelFactory;
use Hawksama\ProductRuleNotifier\Model\NoticeRepository as ModelRepository;
use Hawksama\ProductRuleNotifier\Api\Data\NoticeInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

/**
 * Edit Notification entity backend controller.
 */
class Edit extends \Hawksama\ProductRuleNotifier\Controller\Adminhtml\Notice implements HttpGetActionInterface
{
    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory,
        private readonly ModelFactory $noticeFactory,
        private readonly ModelRepository $repository
    ) {
        parent::__construct($context);
    }

    /**
     * @throws NoSuchEntityException
     */
    public function execute(): Page|ResultInterface
    {
        /** @var NoticeInterface $model */
        $model = $this->noticeFactory->create();
        $id = $this->getRequest()->getParam(NoticeInterface::NOTICE_ID);

        if ($id) {
            $model = $this->repository->getById($id);
            if (!$model->getNoticeId()) {
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
            $model->getNoticeId() ?
                (string) $model->getRuleName() :
                __('New Product Rule Notification')->render()
        );
        return $resultPage;
    }
}
