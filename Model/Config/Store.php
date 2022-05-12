<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Store
{
    private const XML_PATH_WEB_BASE_URL = 'web/secure/base_url';

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
        return $this->scopeConfig->getValue(
            self::XML_PATH_WEB_BASE_URL
        );
    }
}
