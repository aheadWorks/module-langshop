<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\Saas\Url\Param;

use Aheadworks\Langshop\Model\Config\Store as StoreConfig;
use Aheadworks\Langshop\Model\Service\Integration;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\HTTP\Client\CurlFactory;

class Builder
{
    /**
     * @var Integration
     */
    private Integration $integration;

    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var StoreConfig
     */
    private StoreConfig $config;

    /**
     * @param Integration $integration
     * @param Session $session
     * @param StoreConfig $config
     */
    public function __construct(
        Integration $integration,
        Session $session,
        StoreConfig $config
    ) {
        $this->integration = $integration;
        $this->session = $session;
        $this->config = $config;
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
            'domain' => str_replace(
                ['http://', 'https://'],
                '',
                $this->config->getDefaultBaseUrl()
            ),
            'email' => $this->session->getUser()->getEmail(),
            'token' => $this->integration->getAccessToken(),
            'rest_path' => '/rest/all/V1/awLangshop'
        ];
    }
}
