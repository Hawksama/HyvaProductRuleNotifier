<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\ProductRuleNotifier\Controller\Adminhtml\Notification;

use Hawksama\ProductRuleNotifier\Controller\Adminhtml\Controller;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * New action Notification controller.
 */
class NewAction extends Controller implements HttpGetActionInterface
{
    public function execute(): Page|ResultInterface
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage = $this->initPage($resultPage);
        $resultPage->getConfig()->getTitle()->prepend(__('New Product Rule Notifier')->render());

        return $resultPage;
    }
}
