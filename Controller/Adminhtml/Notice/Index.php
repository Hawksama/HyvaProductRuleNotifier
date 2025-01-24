<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Controller\Adminhtml\Notice;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Notice backend index (list) controller.
 */
class Index extends \Hawksama\Notice\Controller\Adminhtml\Notice implements HttpGetActionInterface
{
    public function execute(): ResultInterface|ResponseInterface
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Hawksama_Notice::management');
        $resultPage->addBreadcrumb(__('Notice'), __('Notice'));
        $resultPage->addBreadcrumb(__('Manage Notices'), __('Manage Notices'));
        $resultPage->getConfig()->getTitle()->prepend(__('Notice List'));

        return $resultPage;
    }
}
