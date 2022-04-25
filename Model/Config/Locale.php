<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Locale
{
    private const XML_PATH_CONFIG = 'general/locale/code';

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
     * Retrieves locale for the store
     *
     * @param int $storeId
     * @return string|null
     */
    public function getValue(int $storeId): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONFIG,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
