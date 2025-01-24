<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Controller\Adminhtml\Notice;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * New action Notice controller.
 */
class NewAction extends \Hawksama\Notice\Controller\Adminhtml\Notice implements HttpGetActionInterface
{
    public function execute(): Page|ResultInterface
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Hawksama_Notice::management');
        $resultPage->getConfig()->getTitle()->prepend(__('New Notice'));

        return $resultPage;
    }
}
