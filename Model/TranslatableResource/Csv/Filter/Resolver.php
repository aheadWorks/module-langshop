<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\TranslatableResource\Csv\Filter;

use Magento\Framework\Api\Filter;
use Magento\Framework\DataObject;

class Resolver
{
    private const DEFAULT_FILTER = 'eq';

    /**
     * @var FilterApplierInterface[]
     */
    private array $filterAppliers;

    /**
     * @param FilterApplierInterface[] $filterAppliers
     */
    public function __construct(
        array $filterAppliers
    ) {
        $this->filterAppliers = $filterAppliers;
    }

    /**
     * Resolve
     *
     * @param DataObject[]|Filter[] $filters
     * @param DataObject $object
     * @return bool
     */
    public function resolve(array $filters, DataObject $object): bool
    {
        $result = true;

        foreach ($filters as $filter) {
            /** @var string|array $value */
            $value = $filter->getValue();
            $preparedValueList = is_array($value) ? $value : [$value];
            foreach ($preparedValueList as $condition => $conditionValue) {
                $applier = $this->filterAppliers[$condition] ?? $this->filterAppliers[self::DEFAULT_FILTER];
                $result =  $result && $applier->apply($object->getData($filter->getField()), $conditionValue);
            }
        }

        return $result;
    }
}
