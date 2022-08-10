<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Store
{
    private const XML_PATH_WEB_BASE_UNSECURE_URL = 'web/unsecure/base_url';
    private const XML_PATH_WEB_BASE_SECURE_URL = 'web/secure/base_url';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get default base url
     *
     * @return string|null
     */
    public function getDefaultBaseUrl(): ?string
    {
        $url = $this->scopeConfig->getValue(
            self::XML_PATH_WEB_BASE_SECURE_URL
        );

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $url = $this->scopeConfig->getValue(
                self::XML_PATH_WEB_BASE_UNSECURE_URL
            );
        }

        return $url;
    }
}
