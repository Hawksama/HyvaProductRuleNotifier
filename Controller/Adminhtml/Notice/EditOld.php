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
 * Edit Notice entity backend controller.
 */
class EditOld extends Action implements HttpGetActionInterface
{
	/**
	 * Authorization level of a basic admin session.
	 *
	 * @see _isAllowed()
	 */
	public const ADMIN_RESOURCE = 'Hawksama_Notice::management';

	public function execute(): Page|ResultInterface
	{
		/** @var Page $resultPage */
		$resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
		$resultPage->setActiveMenu('Hawksama_Notice::management');
		$resultPage->getConfig()->getTitle()->prepend(__('Edit Notice'));

		return $resultPage;
	}
}
