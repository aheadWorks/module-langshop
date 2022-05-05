<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\Oauth\Exception;
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Api\OauthServiceInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;
use Magento\Integration\Model\Integration as IntegrationModel;
use Magento\Integration\Model\Oauth\Token\Provider as TokenProvider;

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
     * @var TokenProvider
     */
    private TokenProvider $tokenProvider;

    /**
     * @param ConfigBasedIntegrationManager $integrationManager
     * @param IntegrationServiceInterface $integrationService
     * @param OauthServiceInterface $oauthService
     * @param TokenProvider $tokenProvider
     */
    public function __construct(
        ConfigBasedIntegrationManager $integrationManager,
        IntegrationServiceInterface $integrationService,
        OauthServiceInterface $oauthService,
        TokenProvider $tokenProvider
    ) {
        $this->integrationManager = $integrationManager;
        $this->integrationService = $integrationService;
        $this->oauthService = $oauthService;
        $this->tokenProvider = $tokenProvider;
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
     * @return array
     * @throws Exception
     * @throws IntegrationException
     */
    public function getAccessToken(): array
    {
        $integration = $this->getIntegration();
        $consumer = $this->tokenProvider->getConsumerByKey($integration->getData('consumer_key'));

        return $this->tokenProvider->getAccessToken($consumer);
    }
}
