<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\Notice\Controller\Check;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Hawksama\Notice\ViewModel\NoticeViewModel;

class Notices implements HttpGetActionInterface
{
    public function __construct(
        private readonly JsonFactory $jsonFactory,
        private readonly NoticeViewModel $noticeViewModel
    ) {
    }

    public function execute(): Json
    {
        $result = $this->jsonFactory->create();

        try {
            $notices = $this->noticeViewModel->getNotices();
        } catch (\Exception $e) {
            return $result->setData(['error' => __('Unable to fetch notices.')]);
        }

        return $result->setData(['notices' => $notices]);
    }
}
