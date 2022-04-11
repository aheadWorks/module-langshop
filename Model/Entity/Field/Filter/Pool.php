<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Filter;

class Pool
{
    /**
     * @var AbstractFilter[]
     */
    private $filters;

    /**
     * @param AbstractFilter[] $filters
     */
    public function __construct(
        array $filters = []
    ) {
        $this->filters = $filters;
    }

    /**
     * Get filter
     *
     * @param string $filterType
     * @return AbstractFilter
     */
    public function get($filterType)
    {
        return isset($this->filters[$filterType])
            ? $this->filters[$filterType]
            : $this->filters['text'];
    }
}
