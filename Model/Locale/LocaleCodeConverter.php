<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale;

class LocaleCodeConverter
{
    /**
     * Converts the locale code to Langshop format
     *
     * @param string $localeCode
     * @return string
     */
    public function toLangshop(string $localeCode): string
    {
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
        return str_replace('-', '_', $localeCode);
    }
}
