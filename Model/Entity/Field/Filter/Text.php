<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Text implements PreparerInterface
{
    /**
     * @inheritDoc
     */
    public function getPreparedConditionType($value): string
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
