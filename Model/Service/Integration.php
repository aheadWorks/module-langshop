<?php
namespace Aheadworks\Langshop\Model\Service;

use Magento\Framework\Exception\IntegrationException;
use Magento\Integration\Model\ConfigBasedIntegrationManager;
use Magento\Integration\Api\OauthServiceInterface;
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Model\Integration as IntegrationModel;
use Magento\Integration\Model\Oauth\Token\Provider as TokenProvider;
use Magento\Framework\Oauth\Exception;

class Integration
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
     * @var TokenProvider
     */
    private $tokenProvider;

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
     * Create Langshop integration and generate access token
     *
     * @return void
     * @throws \Exception
     */
    public function createIntegrationAndGenerateToken()
    {
        $this->integrationManager->processIntegrationConfig([self::INTEGRATION_NAME]);
        $integration = $this->getIntegration();
        if ($this->oauthService->createAccessToken($integration->getData(IntegrationModel::CONSUMER_ID))) {
            $integration->setData(IntegrationModel::STATUS, IntegrationModel::STATUS_ACTIVE)->save();
        }
    }

    /**
     * Get Langshop integration
     *
     * @return IntegrationModel
     * @throws IntegrationException
     */
    public function getIntegration()
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
    public function getAccessToken()
    {
        $integration = $this->getIntegration();
        $consumer = $this->tokenProvider->getConsumerByKey($integration->getData('consumer_key'));

        return $this->tokenProvider->getAccessToken($consumer);
    }
}
