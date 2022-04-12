<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Text implements PreparerInterface
{
    /**
     * @inheritDoc
     */
    public function getPreparedConditionType($value)
    {
        return 'like';
    }

    /**
     * @inheritDoc
     */
    public function getPreparedValue($value)
    {
        return sprintf(
            '%%%s%%',
            str_replace(['%', '_'], ['\%', '\_'], $value)
        );
    }
}
