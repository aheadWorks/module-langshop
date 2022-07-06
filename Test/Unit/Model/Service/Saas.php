<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Test\Unit\Model\Service;

use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface;
use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterfaceFactory;
use Aheadworks\Langshop\Api\Data\Saas\UrlResultInterface;
use Aheadworks\Langshop\Api\Data\Saas\UrlResultInterfaceFactory;
use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;
use Aheadworks\Langshop\Model\Service\Saas as SaasService;
use Magento\Backend\Model\UrlInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class Saas extends TestCase
{
    /**
     * @var SaasService
     */
    private SaasService $saasService;

    /**
     * @var MockObject
     */
    private MockObject $confirmationResultFactoryMock;

    /**
     * @var MockObject
     */
    private MockObject $urlResultFactoryMock;

    /**
     * @var UrlInterface|MockObject
     */
    private MockObject $urlBuilderMock;

    /**
     * @var SaasConfig|MockObject
     */
    private MockObject $saasConfigMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->confirmationResultFactoryMock = $this->createMock(ConfirmationResultInterfaceFactory::class);
        $this->urlResultFactoryMock = $this->createMock(UrlResultInterfaceFactory::class);
        $this->urlBuilderMock = $this->createMock(UrlInterface::class);
        $this->saasConfigMock = $this->createMock(SaasConfig::class);

        $this->saasService = new SaasService(
            $this->confirmationResultFactoryMock,
            $this->urlResultFactoryMock,
            $this->urlBuilderMock,
            $this->saasConfigMock
        );
    }

    /**
     * Test 'saveKey' method
     *
     * @return void
     */
    public function testSaveKey(): void
    {
        $result = $this->createMock(ConfirmationResultInterface::class);
        $key = 'key';

        $this->saasConfigMock
            ->expects($this->once())
            ->method('savePublicKey')
            ->with($key);
        $this->confirmationResultFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($result);
        $result
            ->expects($this->once())
            ->method('setSuccess')
            ->with(true)
            ->willReturn($result);

        $this->assertSame($result, $this->saasService->saveKey($key));
    }

    /**
     * Test 'getDashboardUrl' method
     *
     * @return void
     */
    public function testGetDashboardUrl(): void
    {
        $result = $this->createMock(UrlResultInterface::class);
        $routePath = 'admin/dashboard';
        $url = 'url';

        $this->urlBuilderMock
            ->expects($this->once())
            ->method('turnOffSecretKey')
            ->willReturn($this->urlBuilderMock);
        $this->urlBuilderMock
            ->expects($this->once())
            ->method('getUrl')
            ->with($routePath)
            ->willReturn($url);
        $this->urlResultFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($result);
        $result
            ->expects($this->once())
            ->method('setUrl')
            ->with($url)
            ->willReturn($result);

        $this->assertSame($result, $this->saasService->getDashboardUrl());
    }
}
