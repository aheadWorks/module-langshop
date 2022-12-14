<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv;

use Magento\Framework\Api\SearchCriteriaInterface;

class Composite implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private array $processors;

    /**
     * @param ProcessorInterface[] $processors
     */
    public function __construct(
        array $processors = []
    ) {
        $this->processors = $processors;
    }

    /**
     * Apply Search Criteria to Collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @throws \InvalidArgumentException
     * @return void
     */
    public function process(SearchCriteriaInterface $searchCriteria, Collection $collection): void
    {
        foreach ($this->processors as $name => $processor) {
            if (!($processor instanceof ProcessorInterface)) {
                throw new \InvalidArgumentException(
                    sprintf('Processor %s must implement %s interface.', $name, ProcessorInterface::class)
                );
            }
            $processor->process($searchCriteria, $collection);
        }
    }
}
