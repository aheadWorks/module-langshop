<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\TranslatableResource\Csv\Filter;

interface FilterApplierInterface
{
    /**
     * Apply filter to data object
     *
     * @param string $value
     * @param string $conditionValue
     * @return bool
     */
    public function apply(string $value, string $conditionValue): bool;
}
