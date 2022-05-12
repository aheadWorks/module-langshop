<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas\Url\Param;

use Aheadworks\Langshop\Model\Config\Store as StoreConfig;
use Aheadworks\Langshop\Model\Service\Integration as IntegrationService;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Framework\Exception\IntegrationException;

class Builder
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
     * @param IntegrationService $integrationService
     * @param AuthSession $authSession
     * @param StoreConfig $storeConfig
     */
    public function __construct(
        IntegrationService $integrationService,
        AuthSession $authSession,
        StoreConfig $storeConfig
    ) {
        $this->integrationService = $integrationService;
        $this->authSession = $authSession;
        $this->storeConfig = $storeConfig;
    }

    /**
     * Build params for magento install request
     *
     * @return array
     * @throws IntegrationException
     */
    public function buildForMagentoInstallRequest(): array
    {
        return [
            'domain' => $this->storeConfig->getDefaultBaseUrl(),
            'email' => $this->authSession->getUser()->getEmail(),
            'token' => $this->integrationService->getAccessToken(),
            'rest_path' => '/rest/all/V1/awLangshop'
        ];
    }
}
