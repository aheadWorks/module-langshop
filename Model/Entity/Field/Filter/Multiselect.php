<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Multiselect implements PreparerInterface
{
    /**
     * @inheritDoc
     */
    public function getPreparedConditionType($value)
    {
        return is_array($value) ? 'in' : 'finset';
    }

    /**
     * @inheritDoc
     */
    public function getPreparedValue($value)
    {
        return $value;
    }
}
