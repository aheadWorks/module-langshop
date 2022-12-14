<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Magento\Framework\Exception\IntegrationException;
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Api\OauthServiceInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;
use Magento\Integration\Model\Integration as IntegrationModel;

class Integration
{
    public const INTEGRATION_NAME = 'Langshop';

    /**
     * @var ConfigBasedIntegrationManager
     */
    private ConfigBasedIntegrationManager $integrationManager;

    /**
     * @var IntegrationServiceInterface
     */
    private IntegrationServiceInterface $integrationService;

    /**
     * @var OauthServiceInterface
     */
    private OauthServiceInterface $oauthService;

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
     * Create Langshop integration
     *
     * @return void
     */
    public function createIntegration(): void
    {
        $this->integrationManager->processIntegrationConfig([self::INTEGRATION_NAME]);
    }

    /**
     * Generate access token
     *
     * @return bool
     * @throws IntegrationException
     */
    public function generateToken(): bool
    {
        $integration = $this->getIntegration();
        $isCreated = $this->oauthService->createAccessToken($integration->getData(IntegrationModel::CONSUMER_ID));
        if ($isCreated) {
            $integration->setData(IntegrationModel::STATUS, IntegrationModel::STATUS_ACTIVE)->save();
        }

        return $isCreated;
    }

    /**
     * Get Langshop integration
     *
     * @return IntegrationModel
     * @throws IntegrationException
     */
    public function getIntegration(): IntegrationModel
    {
        $integrationId = $this->integrationService->findByName(self::INTEGRATION_NAME)->getId();

        return $this->integrationService->get($integrationId);
    }

    /**
     * Get access token
     *
     * @return string
     * @throws IntegrationException
     */
    public function getAccessToken(): string
    {
        return $this->getIntegration()->getData('token');
    }

    /**
     * Delete Langshop integration
     *
     * @return void
     * @throws IntegrationException
     */
    public function deleteIntegration(): void
    {
        $integration = $this->getIntegration();
        $integrationData = $this->integrationService->delete($integration->getId());
        if (isset($integrationData[IntegrationModel::CONSUMER_ID])) {
            $this->oauthService->deleteConsumer($integrationData[IntegrationModel::CONSUMER_ID]);
        }
    }
}
