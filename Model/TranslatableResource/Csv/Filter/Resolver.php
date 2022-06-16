<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\TranslatableResource\Csv\Filter;

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
     * @param DataObject[] $filters
     * @param DataObject $object
     * @return bool
     */
    public function resolve(array $filters, DataObject $object): bool
    {
        $result = true;

        foreach ($filters as $filter) {
            $value = $filter->getValue();
            if (is_array($value)) {
                foreach ($value as $condition => $conditionValue) {
                    $applier = $this->filterAppliers[$condition] ?? $this->filterAppliers[self::DEFAULT_FILTER];
                    $result =  $result && $applier->apply($object->getData($filter->getField()), $conditionValue);
                }
            } else {
                $applier = $this->filterAppliers[self::DEFAULT_FILTER];
                $result = $result && $applier->apply($object->getData($filter->getField()), $value);
            }
        }

        return $result;
    }
}
