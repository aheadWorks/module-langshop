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
    private FilterBuilder $filterBuilder;

    /**
     * @var PreparerInterface[]
     */
    private array $preparers;

    /**
     * @param FilterBuilder $filterBuilder
     * @param PreparerInterface[] $preparers
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
     * @param string[] $value
     * @param string $filterType
     * @return Filter
     */
    public function create(
        string $field,
        array $value,
        string $filterType = 'text'
    ): Filter {
        $preparer = $this->preparers[$filterType] ?? $this->preparers['text'];

        $conditionType = $preparer->getPreparedConditionType($value);
        $value = $preparer->getPreparedValue($value);

        return $this->filterBuilder->setConditionType($conditionType)
            ->setField($field)
            ->setValue($value)
            ->create();
    }
}
