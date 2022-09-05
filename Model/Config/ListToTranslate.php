<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ListToTranslate
{
    private const XML_PATH_CONFIG = 'aw_ls/general/scope_list_to_translate';
    private const SEPARATOR = ',';

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
     * Retrieves translatable scopes from config
     *
     * @return array
     */
    public function getValue(): array
    {
        return array_filter(explode(
            self::SEPARATOR,
            $this->scopeConfig->getValue(self::XML_PATH_CONFIG) ?? ''
        ));
    }
}
