<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\TranslatableResource\Csv\Filter;

class Eq implements FilterApplierInterface
{
    /**
     * Check is value equal condition value
     *
     * @param string $value
     * @param string $conditionValue
     * @return bool
     */
    public function apply(string $value, string $conditionValue): bool
    {
        return $value === $conditionValue;
    }
}
