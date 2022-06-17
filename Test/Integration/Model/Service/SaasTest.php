<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Integration\Model\Service;

use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;
use Aheadworks\Langshop\Model\Service\Saas as SaasService;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use PHPUnit\Framework\TestCase;

class SaasTest extends TestCase
{
    /**
     * @var ObjectManagerInterface|null
     */
    private ?ObjectManagerInterface $objectManager;

    /**
     * @var SaasService|null
     */
    private ?SaasService $saasService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();
        $this->saasService = $this->objectManager->create(SaasService::class);
    }

    /**
     * @return void
     */
    public function testSaveKey(): void
    {
        $publicKey = 'public_key';
        $this->saasService->saveKey($publicKey);

        /** @var SaasConfig $saasConfig */
        $saasConfig = $this->objectManager->get(SaasConfig::class);

        $this->assertSame($publicKey, $saasConfig->getPublicKey());
    }

    /**
     * @return void
     */
    public function testGetDashboardUrl(): void
    {
        $dashboardUrl = $this->saasService->getDashboardUrl()->getUrl();

        $this->assertStringContainsString(
            '/admin/dashboard',
            $dashboardUrl
        );
    }
}
