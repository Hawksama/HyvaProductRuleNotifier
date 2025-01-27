<?php

declare(strict_types=1);

namespace Hawksama\HyvaProductRuleNotifier\Test\Unit\Controller\Check;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Hawksama\HyvaProductRuleNotifier\Controller\Check\Notifications;
use Hawksama\HyvaProductRuleNotifier\ViewModel\NotificationViewModel;
use Hawksama\HyvaProductRuleNotifier\Api\Data\NotificationInterface;
use PHPUnit\Framework\TestCase;
use TddWizard\Fixtures\Catalog\ProductBuilder;
use TddWizard\Fixtures\Catalog\ProductFixture;

class NotificationsTest extends TestCase
{
    /** @var Notifications */
    private $controller;

    /** @var JsonFactory|\PHPUnit\Framework\MockObject\MockObject */
    private $jsonFactoryMock;

    /** @var NotificationViewModel|\PHPUnit\Framework\MockObject\MockObject */
    private $notificationViewModelMock;

    /** @var ProductFixture */
    private $productFixture;

    protected function setUp(): void
    {
        $this->jsonFactoryMock = $this->createMock(JsonFactory::class);
        $this->notificationViewModelMock = $this->createMock(NotificationViewModel::class);

        $this->controller = new Notifications(
            $this->jsonFactoryMock,
            $this->notificationViewModelMock
        );

        // Create a product fixture for testing
        $this->productFixture = new ProductFixture(
            ProductBuilder::aSimpleProduct()->build()
        );
    }

    public function testExecuteReturnsNotifications(): void
    {
        $notificationMock = $this->createMock(NotificationInterface::class);
        $notificationMock->method('getDescription')->willReturn('Test Notification');
        $notificationMock->method('getNotificationType')->willReturn(2);

        $this->notificationViewModelMock
            ->method('getNotifications')
            ->willReturn([$notificationMock]);

        $jsonMock = $this->createMock(Json::class);
        $jsonMock->expects($this->once())
            ->method('setData')
            ->with([
                'notifications' => [
                    [
                        'message' => 'Test Notification',
                        'type' => 2
                    ],
                ],
            ])
            ->willReturnSelf();

        $this->jsonFactoryMock
            ->method('create')
            ->willReturn($jsonMock);

        $result = $this->controller->execute();

        $this->assertSame($jsonMock, $result);
    }

    public function testExecuteHandlesException(): void
    {
        $this->notificationViewModelMock
            ->method('getNotifications')
            ->willThrowException(new LocalizedException(new Phrase('Test Error')));

        $jsonMock = $this->createMock(Json::class);
        $jsonMock->expects($this->once())
            ->method('setData')
            ->with(['error' => 'Unable to fetch notifications.'])
            ->willReturnSelf();

        $this->jsonFactoryMock
            ->method('create')
            ->willReturn($jsonMock);

        $result = $this->controller->execute();

        $this->assertSame($jsonMock, $result);
    }
}
