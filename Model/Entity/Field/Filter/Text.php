<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Text extends AbstractFilter
{
    /**
     * @inheritDoc
     */
    public function prepare(&$value, &$conditionType)
    {
        $conditionType = 'like';
        $value = sprintf(
            '%%%s%%',
            str_replace(['%', '_'], ['\%', '\_'], $value)
        );
    }
}
