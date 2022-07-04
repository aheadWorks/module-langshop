<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Select implements PreparerInterface
{
    /**
     * Get prepared condition type
     *
     * @param string[] $value
     * @return string
     */
    public function getPreparedConditionType(array $value): string
    {
        return 'in';
    }

    /**
     * Get prepared value
     *
     * @param string[] $value
     * @return string[]
     */
    public function getPreparedValue(array $value)
    {
        return $value;
    }
}
