<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas\Request;

use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;
use Aheadworks\Langshop\Model\Config\Store as StoreConfig;
use Aheadworks\Langshop\Model\Service\Integration as IntegrationService;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Framework\Exception\IntegrationException;

class Install
{
    /**
     * @var IntegrationService
     */
    private IntegrationService $integrationService;

    /**
     * @var AuthSession
     */
    private AuthSession $authSession;

    /**
     * @var StoreConfig
     */
    private StoreConfig $storeConfig;

    /**
     * @var SaasConfig
     */
    private SaasConfig $saasConfig;

    /**
     * @param IntegrationService $integrationService
     * @param AuthSession $authSession
     * @param StoreConfig $storeConfig
     * @param SaasConfig $saasConfig
     */
    public function __construct(
        IntegrationService $integrationService,
        AuthSession $authSession,
        StoreConfig $storeConfig,
        SaasConfig $saasConfig
    ) {
        $this->integrationService = $integrationService;
        $this->authSession = $authSession;
        $this->storeConfig = $storeConfig;
        $this->saasConfig = $saasConfig;
    }

    /**
     * Retrieves URL for the Magento install request
     *
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf('%smagento/install', $this->saasConfig->getDomain());
    }

    /**
     * Retrieves parameters for the Magento install request
     *
     * @return array
     * @throws IntegrationException
     */
    public function getParams(): array
    {
        return [
            'domain' => $this->storeConfig->getDefaultBaseUrl(),
            'email' => $this->authSession->getUser()->getEmail(),
            'token' => $this->integrationService->getAccessToken(),
            'rest_path' => '/rest/all/V1/awLangshop'
        ];
    }
}
