<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Validation;

use Aheadworks\Langshop\Model\TranslatableResource\LocaleScope;
use Magento\Framework\Exception\NoSuchEntityException;

class Locale
{
    /**
     * @var LocaleScope
     */
    private LocaleScope $localeScope;

    /**
     * @param LocaleScope $localeScope
     */
    public function __construct(
        LocaleScope $localeScope
    ) {
        $this->localeScope = $localeScope;
    }

    /**
     * Validates locale code
     *
     * @param string $value
     * @throws NoSuchEntityException
     */
    public function validate(string $value): void
    {
        $locales = [];
        foreach ($this->localeScope->getList() as $locale) {
            $locales[] = $locale->getLocaleCode();
        }

        if (!in_array($value, $locales)) {
            throw new NoSuchEntityException(
                __('Locale with code = "%1" does not exist.', $value)
            );
        }
    }
}
