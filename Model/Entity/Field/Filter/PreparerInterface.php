<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

interface PreparerInterface
{
    /**
     * Get prepared condition type
     *
     * @param string|array $value
     * @return string
     */
    public function getPreparedConditionType($value);

    /**
     * Get prepared value
     *
     * @param string|array $value
     * @return string|array
     */
    public function getPreparedValue($value);
}
