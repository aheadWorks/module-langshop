<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Validation;

use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface;
use Aheadworks\Langshop\Model\TranslatableResource\EntityAttribute;
use Aheadworks\Langshop\Model\TranslatableResource\LocaleScope;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Translation
{
    /**
     * @var LocaleScope
     */
    private LocaleScope $localeScope;

    /**
     * @var EntityAttribute
     */
    private EntityAttribute $entityAttribute;

    /**
     * @param LocaleScope $localeScope
     * @param EntityAttribute $entityAttribute
     */
    public function __construct(
        LocaleScope $localeScope,
        EntityAttribute $entityAttribute
    ) {
        $this->localeScope = $localeScope;
        $this->entityAttribute = $entityAttribute;
    }

    /**
     * Checks if the translation has proper data to use
     *
     * @param TranslationInterface $translation
     * @param string $resourceType
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function validate(TranslationInterface $translation, string $resourceType): void
    {
        $locales = [];
        foreach ($this->localeScope->getList() as $locale) {
            $locales[] = $locale->getLocaleCode();
        }

        $attributes = [];
        foreach ($this->entityAttribute->getList($resourceType) as $attribute) {
            $attributes[] = $attribute->getCode();
        }

        if (!in_array($translation->getLocale(), $locales)) {
            throw new NoSuchEntityException(
                __('Locale with code = "%1" does not exist.', $translation->getLocale())
            );
        }

        if (!in_array($translation->getKey(), $attributes)) {
            throw new NoSuchEntityException(
                __('Attribute with code = "%1" does not exist.', $translation->getKey())
            );
        }
    }
}
