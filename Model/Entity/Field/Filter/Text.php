<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Text implements PreparerInterface
{
    /**
     * Get prepared condition type
     *
     * @param string[] $value
     * @return string
     */
    public function getPreparedConditionType(array $value): string
    {
        return 'like';
    }

    /**
     * Get prepared value
     *
     * @param string[] $value
     * @return string
     */
    public function getPreparedValue(array $value)
    {
        return sprintf(
            '%%%s%%',
            str_replace(['%', '_'], ['\%', '\_'], current($value))
        );
    }
}
