<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Source;

use Aheadworks\Langshop\Model\Config\Locale as LocaleConfig;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Locale\ResolverInterface as LocaleResolverInterface;
use Magento\Store\Model\System\Store as StoreOptions;

class ListToTranslate implements OptionSourceInterface
{
    /**
     * @var StoreOptions
     */
    private StoreOptions $storeOptions;

    /**
     * @var LocaleConfig
     */
    private LocaleConfig $localeConfig;

    /**
     * @var LocaleResolverInterface
     */
    private LocaleResolverInterface $localeResolver;

    /**
     * @param StoreOptions $storeOptions
     * @param LocaleConfig $localeConfig
     * @param LocaleResolverInterface $localeResolver
     */
    public function __construct(
        StoreOptions $storeOptions,
        LocaleConfig $localeConfig,
        LocaleResolverInterface $localeResolver
    ) {
        $this->storeOptions = $storeOptions;
        $this->localeConfig = $localeConfig;
        $this->localeResolver = $localeResolver;
    }

    /**
     * Retrieves array of stores
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->disableOptions(
            $this->storeOptions->toOptionArray(),
            $this->localeResolver->getDefaultLocale()
        );
    }

    /**
     * Disables options-stores with default locale
     *
     * @param array $options
     * @param string $defaultLocale
     * @return array
     */
    private function disableOptions(array $options, string $defaultLocale): array
    {
        foreach ($options as &$option) {
            if (is_array($option['value'])) {
                $option['value'] = $this->disableOptions($option['value'], $defaultLocale);
            } else {
                $locale = $this->localeConfig->getValue((int) $option['value']);
                $option['disabled'] = $locale === $defaultLocale;
            }
        }

        return $options;
    }
}
