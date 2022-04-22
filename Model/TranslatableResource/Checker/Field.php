<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Checker;

class Field
{
    /**
     * Сan contain options
     *
     * @param string $fieldType
     * @return bool
     */
    public function canContainOptions(string $fieldType): bool
    {
        return in_array($fieldType, ['select', 'multiselect']);
    }
}
