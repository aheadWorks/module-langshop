<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\TranslatableResource\Csv\Filter;

class Like implements FilterApplierInterface
{
    /**
     * Check is value like condition value
     *
     * @param string $value
     * @param string $conditionValue
     * @return bool
     */
    public function apply(string $value, string $conditionValue): bool
    {
        return is_int(stripos($value, trim($conditionValue, '%')));
    }
}
