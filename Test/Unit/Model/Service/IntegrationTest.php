<?php
namespace Aheadworks\Langshop\Test\Unit\Model\Service;

use Aheadworks\Langshop\Model\Service\Integration as IntegrationService;
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Api\OauthServiceInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;
use Magento\Integration\Model\Integration;
use Magento\Integration\Model\Integration as IntegrationModel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    /**
     * @var IntegrationService
     */
    private IntegrationService $integrationService;

    /**
     * @var ConfigBasedIntegrationManager|MockObject
     */
    private $integrationManagerMock;

    /**
     * @var IntegrationServiceInterface|MockObject
     */
    private $magentoIntegrationServiceMock;

    /**
     * @var OauthServiceInterface|MockObject
     */
    private $oauthServiceMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->integrationManagerMock = $this->createMock(ConfigBasedIntegrationManager::class);
        $this->magentoIntegrationServiceMock = $this->createMock(IntegrationServiceInterface::class);
        $this->oauthServiceMock = $this->createMock(OauthServiceInterface::class);

        $this->integrationService = new IntegrationService(
            $this->integrationManagerMock,
            $this->magentoIntegrationServiceMock,
            $this->oauthServiceMock
        );
    }

    public function testCreateIntegrationAndGenerateToken()
    {
        $this->integrationManagerMock
            ->expects($this->once())
            ->method('processIntegrationConfig')
            ->with([IntegrationService::INTEGRATION_NAME])
            ->willReturn([IntegrationService::INTEGRATION_NAME]);

        $this->integrationService->createIntegration();
    }

    public function testGenerateToken()
    {
        $consumerId = 1;
        $integrationMock = $this->testGetIntegration();
        $integrationMock
            ->expects($this->once())
            ->method('getData')
            ->with(IntegrationModel::CONSUMER_ID)
            ->willReturn($consumerId);
        $this->oauthServiceMock
            ->expects($this->once())
            ->method('createAccessToken')
            ->with($consumerId)
            ->willReturn(true);
        $integrationMock
            ->expects($this->once())
            ->method('setData')
            ->with(IntegrationModel::STATUS, Integration::STATUS_ACTIVE)
            ->willReturn($integrationMock);
        $integrationMock
            ->expects($this->once())
            ->method('save')
            ->willReturn($integrationMock);

        $this->integrationService->generateToken();
    }

    public function testGetIntegration()
    {
        $integrationMock = $this->createMock(Integration::class);
        $id = 1;

        $this->magentoIntegrationServiceMock
            ->expects($this->any())
            ->method('findByName')
            ->with(IntegrationService::INTEGRATION_NAME)
            ->willReturn($integrationMock);
        $integrationMock
            ->expects($this->any())
            ->method('getId')
            ->willReturn($id);
        $this->magentoIntegrationServiceMock
            ->expects($this->any())
            ->method('get')
            ->with($id)
            ->willReturn($integrationMock);
        $this->assertSame($integrationMock, $this->integrationService->getIntegration());

        return $integrationMock;
    }

    public function testGetAccessToken()
    {
        $integrationMock = $this->testGetIntegration();
        $accessToken = 'access_token';

        $integrationMock
            ->expects($this->once())
            ->method('getData')
            ->with('token')
            ->willReturn($accessToken);
        $this->assertSame($accessToken, $this->integrationService->getAccessToken());
    }
}
