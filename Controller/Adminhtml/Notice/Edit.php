<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Controller\Adminhtml\Notice;

use Hawksama\Notice\Model\NoticeFactory;
use Hawksama\Notice\Model\NoticeRepository;
use Hawksama\Notice\Api\Data\NoticeInterface;
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
 * Edit Notice entity backend controller.
 */
class Edit extends \Hawksama\Notice\Controller\Adminhtml\Notice implements HttpGetActionInterface
{
    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory,
        private readonly NoticeFactory $noticeFactory,
        private readonly NoticeRepository $repository
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
                $this->messageManager->addErrorMessage(__('This entity no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Notice') : __('New Notice'),
            $id ? __('Edit Notice') : __('New Notice')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Blocks'));
        $resultPage->getConfig()->getTitle()->prepend($model->getNoticeId() ? $model->getRuleName() : __('New Notice'));
        return $resultPage;
    }
}
