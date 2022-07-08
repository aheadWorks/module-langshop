<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale;

class LocaleCodeConverter
{
    /**
     * @var array
     */
    private array $threeplyLocales;

    /**
     * @param array $threeplyLocales
     */
    public function __construct(
        array $threeplyLocales = []
    ) {
        $this->threeplyLocales = $threeplyLocales;
    }

    /**
     * Converts the locale code to Langshop format
     *
     * @param string $localeCode
     * @return string
     */
    public function toLangshop(string $localeCode): string
    {
        $localeCode = $this->threeplyLocales[$localeCode] ?? $localeCode;

        return str_replace('_', '-', $localeCode);
    }

    /**
     * Converts the locale code to Magento format
     *
     * @param string $localeCode
     * @return string
     */
    public function toMagento(string $localeCode): string
    {
        $localeCode = array_search($localeCode, $this->threeplyLocales)
            ? array_search($localeCode, $this->threeplyLocales)
            : $localeCode;
        return str_replace('-', '_', $localeCode);
    }
}
