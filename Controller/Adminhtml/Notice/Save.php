<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Controller\Adminhtml\Notice;

use Hawksama\Notice\Api\Data\NoticeInterface;
use Hawksama\Notice\Model\Notice;
use Hawksama\Notice\Model\NoticeFactory;
use Hawksama\Notice\Model\NoticeRepository;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save Notice controller action.
 */
class Save extends \Hawksama\Notice\Controller\Adminhtml\Notice implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly NoticeFactory $noticeFactory,
        private readonly NoticeRepository $repository
    ) {
        parent::__construct($context);
    }

    /**
     * Save Notice Action.
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /** @var \Magento\Framework\HTTP\PhpEnvironment\Request $request */
        $request = $this->getRequest();

        /** @var \Magento\Framework\App\RequestInterface $data */
        $data = $request->getPostValue();

        if ($data) {
            /** @var Notice $model */
            $model = $this->noticeFactory->create();

            $id = $this->getRequest()->getParam('notice_id');
            if ($id) {
                try {
                    $model = $this->repository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage((string) __('This block no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->repository->save($model);
                $this->messageManager->addSuccessMessage(
                    (string) __('The Notice was saved successfully')
                );
                $this->dataPersistor->clear('hawksama_notice_data');
            } catch (CouldNotSaveException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                $this->dataPersistor->set('hawksama_notice_data', $data);
            }

            return $resultRedirect->setPath('*/*/edit', [
                NoticeInterface::NOTICE_ID => $this->getRequest()->getParam(NoticeInterface::NOTICE_ID)
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
