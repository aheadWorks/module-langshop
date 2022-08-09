<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Validation;

use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Attribute
{
    /**
     * @var EntityAttributeProvider
     */
    private EntityAttributeProvider $entityAttributeProvider;

    /**
     * @param EntityAttributeProvider $entityAttributeProvider
     */
    public function __construct(
        EntityAttributeProvider $entityAttributeProvider
    ) {
        $this->entityAttributeProvider = $entityAttributeProvider;
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
        foreach ($this->entityAttributeProvider->getList($resourceType) as $attribute) {
            $attributes[] = $attribute->getCode();
        }

        if (!in_array($value, $attributes)) {
            throw new NoSuchEntityException(
                __('Attribute with code = "%1" does not exist.', $value)
            );
        }
    }
}
