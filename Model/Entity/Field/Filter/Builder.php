<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;

class Builder
{
    /**
     * @var FilterBuilder
     */
    protected FilterBuilder $filterBuilder;

    /**
     * @var PreparerInterface[]
     */
    private array $preparers;

    /**
     * @param FilterBuilder $filterBuilder
     * @param array $preparers
     */
    public function __construct(
        FilterBuilder $filterBuilder,
        array $preparers = []
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->preparers = $preparers;
    }

    /**
     * Create filter
     *
     * @param string $field
     * @param string|array $value
     * @param string $filterType
     * @return Filter
     */
    public function create($field, $value, $filterType = 'text'): Filter
    {
        $preparer = isset($this->preparers[$filterType])
            ? $this->preparers[$filterType]
            : $this->preparers['text'];

        $conditionType = $preparer->getPreparedConditionType($value);
        $value = $preparer->getPreparedValue($value);

        return $this->filterBuilder->setConditionType($conditionType)
            ->setField($field)
            ->setValue($value)
            ->create();
    }
}
