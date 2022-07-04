<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

interface PreparerInterface
{
    /**
     * Get prepared condition type
     *
     * @param string[] $value
     * @return string
     */
    public function getPreparedConditionType(array $value): string;

    /**
     * Get prepared value
     *
     * @param string[] $value
     * @return string|string[]
     */
    public function getPreparedValue(array $value);
}
