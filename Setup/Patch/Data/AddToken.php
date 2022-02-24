<?php
namespace Aheadworks\Langshop\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;
use Magento\Integration\Api\OauthServiceInterface;
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Model\Integration as IntegrationModel;

class AddToken implements DataPatchInterface
{
    const INTEGRATION_NAME = 'Langshop';

    /**
     * @var ConfigBasedIntegrationManager
     */
    private $integrationManager;

    /**
     * @var IntegrationServiceInterface
     */
    private $integrationService;

    /**
     * @var OauthServiceInterface
     */
    private $oauthService;

    /**
     * @param ConfigBasedIntegrationManager $integrationManager
     * @param IntegrationServiceInterface $integrationService
     * @param OauthServiceInterface $oauthService
     */
    public function __construct(
        ConfigBasedIntegrationManager $integrationManager,
        IntegrationServiceInterface $integrationService,
        OauthServiceInterface $oauthService
    ) {
        $this->integrationManager = $integrationManager;
        $this->integrationService = $integrationService;
        $this->oauthService = $oauthService;
    }
    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->integrationManager->processIntegrationConfig([self::INTEGRATION_NAME]);
        $integration = $this->integrationService->findByName(self::INTEGRATION_NAME);
        if ($this->oauthService->createAccessToken($integration->getConsumerId())) {
            $integration->setStatus(IntegrationModel::STATUS_ACTIVE)->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
