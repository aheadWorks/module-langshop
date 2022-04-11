<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Date extends AbstractFilter
{
    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * todo: when the frontend is ready, we will need to check the date format
     */
    public function prepare(&$value, &$conditionType)
    {
        $conditionType = 'eq';
    }
}
