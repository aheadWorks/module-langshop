<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;

abstract class AbstractFilter
{
    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        FilterBuilder $filterBuilder
    ) {
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Create filter
     *
     * @param string $field
     * @param string|array $value
     * @param string $conditionType
     * @return Filter
     */
    public function create($field, $value, $conditionType = 'eq')
    {
        $this->prepare($value, $conditionType);

        return $this->filterBuilder->setConditionType($conditionType)
            ->setField($field)
            ->setValue($value)
            ->create();
    }

    /**
     * Prepare params
     *
     * @param string|array $value
     * @param string $conditionType
     * @return void
     */
    abstract protected function prepare(&$value, &$conditionType);
}
