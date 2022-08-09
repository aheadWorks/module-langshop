<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Validation;

use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Attribute as AttributeValidation;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Locale as LocaleValidation;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Translation
{
    /**
     * @var LocaleValidation
     */
    private LocaleValidation $localeValidation;

    /**
     * @var AttributeValidation
     */
    private AttributeValidation $attributeValidation;

    /**
     * @var EntityAttributeProvider
     */
    private EntityAttributeProvider $entityAttributeProvider;

    /**
     * @param Locale $localeValidation
     * @param Attribute $attributeValidation
     * @param EntityAttributeProvider $entityAttributeProvider
     */
    public function __construct(
        LocaleValidation $localeValidation,
        AttributeValidation $attributeValidation,
        EntityAttributeProvider $entityAttributeProvider
    ) {
        $this->localeValidation = $localeValidation;
        $this->attributeValidation = $attributeValidation;
        $this->entityAttributeProvider = $entityAttributeProvider;
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
        $this->localeValidation->validate($translation->getLocale());
        $this->attributeValidation->validate($translation->getKey(), $resourceType);

        if (!$this->isTranslatable($translation->getKey(), $resourceType)) {
            throw new LocalizedException(__(
                'Attribute with code = "%1" is not available for translate.',
                $translation->getKey()
            ));
        }
    }

    /**
     * Checks if the attribute field is available for translate
     *
     * @param string $attributeCode
     * @param string $resourceType
     * @return bool
     * @throws LocalizedException
     */
    private function isTranslatable(string $attributeCode, string $resourceType): bool
    {
        return in_array($attributeCode, $this->entityAttributeProvider->getCodesOfTranslatableFields($resourceType));
    }
}
