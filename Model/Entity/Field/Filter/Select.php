<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Select implements PreparerInterface
{
    /**
     * @inheritDoc
     */
    public function getPreparedConditionType($value)
    {
        return is_array($value) ? 'in' : 'eq';
    }

    /**
     * @inheritDoc
     */
    public function getPreparedValue($value)
    {
        return $value;
    }
}
