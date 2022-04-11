<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Select extends AbstractFilter
{
    /**
     * @inheritDoc
     */
    public function prepare(&$value, &$conditionType)
    {
        $conditionType = is_array($value)
            ? 'in'
            : 'eq';
    }
}
