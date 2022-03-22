<?php
namespace Aheadworks\Langshop\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type
    as LocaleScopeTypeSourceModel;
use Magento\Framework\Locale\ResolverInterface as LocaleResolverInterface;

class Config
{
    /**
     * Mapping between Langshop scope types and M2 config scope types
     *
     * @var array
     */
    const SCOPE_TYPE_MAPPING = [
        LocaleScopeTypeSourceModel::DEFAULT => ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        LocaleScopeTypeSourceModel::WEBSITE => ScopeInterface::SCOPE_WEBSITE,
        LocaleScopeTypeSourceModel::STORE => ScopeInterface::SCOPE_STORE,
    ];

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var LocaleResolverInterface
     */
    private $localeResolver;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param LocaleResolverInterface $localeResolver
     */
    public function __construct(ScopeConfigInterface $scopeConfig, LocaleResolverInterface $localeResolver)
    {
        $this->scopeConfig = $scopeConfig;
        $this->localeResolver = $localeResolver;
    }

    /**
     * Retrieve scope locale code from the config
     *
     * @param string $scopeType
     * @param int $scopeId
     * @return string
     */
    public function getScopeLocaleCode($scopeType, $scopeId)
    {
        //TODO: consider accurate setting of default value, possible throwing an exception
        $configScopeType = self::SCOPE_TYPE_MAPPING[$scopeType] ?? null;
        $configScopeCode = $scopeId;
        $defaultLocalePath = $this->localeResolver->getDefaultLocalePath();
        return $this->scopeConfig->getValue(
            $defaultLocalePath,
            $configScopeType,
            $configScopeCode
        );
    }
}
