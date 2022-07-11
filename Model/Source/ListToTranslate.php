<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Source;

use Aheadworks\Langshop\Model\Config\Locale as LocaleConfig;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Locale\ListsInterface as LocaleListsInterface;
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
     * @var LocaleListsInterface
     */
    private LocaleListsInterface $localeLists;

    /**
     * @var LocaleResolverInterface
     */
    private LocaleResolverInterface $localeResolver;

    /**
     * @var array<string, string>
     */
    private array $unsupportedLocales;

    /**
     * @param StoreOptions $storeOptions
     * @param LocaleConfig $localeConfig
     * @param LocaleListsInterface $localeLists
     * @param LocaleResolverInterface $localeResolver
     * @param array<string, string> $unsupportedLocales
     */
    public function __construct(
        StoreOptions $storeOptions,
        LocaleConfig $localeConfig,
        LocaleListsInterface $localeLists,
        LocaleResolverInterface $localeResolver,
        array $unsupportedLocales = []
    ) {
        $this->storeOptions = $storeOptions;
        $this->localeConfig = $localeConfig;
        $this->localeLists = $localeLists;
        $this->localeResolver = $localeResolver;
        $this->unsupportedLocales = $unsupportedLocales;
    }

    /**
     * Retrieves array of stores
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->prepareOptions(
            $this->storeOptions->toOptionArray(),
            $this->localeResolver->getDefaultLocale()
        );
    }

    /**
     * Prepares options-stores with default locale
     *
     * @param array $options
     * @param string $defaultLocale
     * @return array
     */
    private function prepareOptions(array $options, string $defaultLocale): array
    {
        foreach ($options as &$option) {
            if (is_array($option['value'])) {
                $option['value'] = $this->prepareOptions($option['value'], $defaultLocale);
            } else {
                $locale = $this->localeConfig->getValue((int) $option['value']);

                $option['label'] = sprintf('%s - %s', $option['label'], $this->getLocaleName($locale));
                $option['disabled'] = $this->isDisabled($locale, $defaultLocale);
            }
        }

        return $options;
    }

    /**
     * Retrieves locale name by its code
     *
     * @param string $locale
     * @return string
     */
    private function getLocaleName(string $locale): string
    {
        foreach ($this->localeLists->getOptionLocales() as $localeOption) {
            if ($localeOption['value'] === $locale) {
                return $localeOption['label'];
            }
        }

        return '';
    }

    /**
     * Is disabled
     *
     * @param string $locale
     * @param string $defaultLocale
     * @return bool
     */
    private function isDisabled(string $locale, string $defaultLocale): bool
    {
        return $locale === $defaultLocale || in_array($locale, $this->unsupportedLocales);
    }
}
