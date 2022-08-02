<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Provider;

use Aheadworks\Langshop\Model\Entity\Field;
use Aheadworks\Langshop\Model\Entity\Pool as EntityPool;
use Aheadworks\Langshop\Model\Source\TranslatableResource\Field as SourceField;
use Magento\Framework\Exception\LocalizedException;

class EntityAttribute
{
    /**
     * @var EntityPool
     */
    private EntityPool $entityPool;

    /**
     * @var array<string, array<int, array<string, Field>>>
     */
    private array $attributes;

    /**
     * @param EntityPool $entityPool
     */
    public function __construct(
        EntityPool $entityPool
    ) {
        $this->entityPool = $entityPool;
    }

    /**
     * Retrieves entity attributes by specific type
     *
     * @param string $entityType
     * @return Field[]
     * @throws LocalizedException
     */
    public function getList(string $entityType): array
    {
        if (!isset($this->attributes[$entityType])) {
            $fields = $this->entityPool->getByType($entityType)->getFields();
            $fields = $this->splitFields($fields);

            $this->attributes[$entityType] = $fields;
        }

        return array_merge(
            $this->attributes[$entityType][SourceField::TRANSLATABLE],
            $this->attributes[$entityType][SourceField::UNTRANSLATABLE]
        );
    }

    /**
     * Get untranslatable fields
     *
     * @param string $entityType
     * @return Field[]
     * @throws LocalizedException
     */
    public function getUntranslatableFields(string $entityType): array
    {
        if (!isset($this->attributes[$entityType])) {
            $this->getList($entityType);
        }

        return $this->attributes[$entityType][SourceField::UNTRANSLATABLE];
    }

    /**
     * Get codes of untranslatable fields
     *
     * @param string $entityType
     * @return string[]
     * @throws LocalizedException
     */
    public function getCodesOfUntranslatableFields(string $entityType): array
    {
        $codes = [];
        foreach ($this->getUntranslatableFields($entityType) as $field) {
            $codes[] = $field->getCode();
        }

        return $codes;
    }

    /**
     * Get translatable fields
     *
     * @param string $entityType
     * @return Field[]
     * @throws LocalizedException
     */
    public function getTranslatableFields(string $entityType): array
    {
        if (!isset($this->attributes[$entityType])) {
            $this->getList($entityType);
        }

        return $this->attributes[$entityType][SourceField::TRANSLATABLE];
    }

    /**
     * Get codes of translatable fields
     *
     * @param string $entityType
     * @return string[]
     * @throws LocalizedException
     */
    public function getCodesOfTranslatableFields(string $entityType): array
    {
        $codes = [];
        foreach ($this->getTranslatableFields($entityType) as $field) {
            $codes[] = $field->getCode();
        }

        return $codes;
    }

    /**
     * Split fields
     *
     * @param Field[] $fields
     * @return array<int, array<string, Field>>
     */
    private function splitFields(array $fields): array
    {
        $result = [
            SourceField::TRANSLATABLE => [],
            SourceField::UNTRANSLATABLE => []
        ];

        foreach ($fields as $code => $field) {
            if ($field->getIsTranslatable()) {
                $result[SourceField::TRANSLATABLE][$code] = $field;
            } else {
                $result[SourceField::UNTRANSLATABLE][$code] = $field;
            }
        }

        return $result;
    }
}
