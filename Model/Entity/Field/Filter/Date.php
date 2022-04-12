<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Date implements PreparerInterface
{
    /**
     * @inheritDoc
     */
    public function getPreparedConditionType($value)
    {
        return 'eq';
    }

    /**
     * @inheritDoc
     * todo: when the frontend is ready, we will need to check the date format
     */
    public function getPreparedValue($value)
    {
        return $value;
    }
}
