<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Multiselect extends AbstractFilter
{
    /**
     * @inheritDoc
     */
    public function prepare(&$value, &$conditionType)
    {
        $conditionType = is_array($value)
            ? 'in'
            : 'finset';
    }
}
