<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Validation;

use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Translation
{
    /**
     * @var Locale
     */
    private Locale $locale;

    /**
     * @var Attribute
     */
    private Attribute $attribute;

    /**
     * @param Locale $locale
     * @param Attribute $attribute
     */
    public function __construct(
        Locale $locale,
        Attribute $attribute
    ) {
        $this->locale = $locale;
        $this->attribute = $attribute;
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
        $this->locale->validate($translation->getLocale());
        $this->attribute->validate($translation->getKey(), $resourceType);
    }
}
