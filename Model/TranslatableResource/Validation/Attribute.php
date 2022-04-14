<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Validation;

use Aheadworks\Langshop\Model\TranslatableResource\EntityAttribute;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Attribute
{
    /**
     * @var EntityAttribute
     */
    private EntityAttribute $entityAttribute;

    /**
     * @param EntityAttribute $entityAttribute
     */
    public function __construct(
        EntityAttribute $entityAttribute
    ) {
        $this->entityAttribute = $entityAttribute;
    }

    /**
     * Validates attribute code
     *
     * @param string $value
     * @param string $resourceType
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function validate(string $value, string $resourceType): void
    {
        $attributes = [];
        foreach ($this->entityAttribute->getList($resourceType) as $attribute) {
            $attributes[] = $attribute->getCode();
        }

        if (!in_array($value, $attributes)) {
            throw new NoSuchEntityException(
                __('Attribute with code = "%1" does not exist.', $value)
            );
        }
    }
}
