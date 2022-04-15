<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Validation;

use Aheadworks\Langshop\Model\TranslatableResource\Provider\LocaleScope as LocaleScopeProvider;
use Magento\Framework\Exception\NoSuchEntityException;

class Locale
{
    /**
     * @var LocaleScopeProvider
     */
    private LocaleScopeProvider $localeScopeProvider;

    /**
     * @param LocaleScopeProvider $localeScopeProvider
     */
    public function __construct(
        LocaleScopeProvider $localeScopeProvider
    ) {
        $this->localeScopeProvider = $localeScopeProvider;
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
        foreach ($this->localeScopeProvider->getList() as $locale) {
            $locales[] = $locale->getLocaleCode();
        }

        if (!in_array($value, $locales)) {
            throw new NoSuchEntityException(
                __('Locale with code = "%1" does not exist.', $value)
            );
        }
    }
}
