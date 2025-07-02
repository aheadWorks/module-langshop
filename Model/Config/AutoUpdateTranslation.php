<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class AutoUpdateTranslation
{
    private const XML_PATH_CONFIG = 'aw_ls/general/is_auto_update_translation_enabled';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Is auto-update translation enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_CONFIG);
    }
}
