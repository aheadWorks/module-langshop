<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor;

use Magento\Framework\Exception\NoSuchEntityException;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;

class Pool
{
    /**
     * @param ProcessorInterface[] $processorList
     */
    public function __construct(
        private readonly array $processorList = []
    ) {
    }

    /**
     * Retrieves processor for the given resource type
     *
     * @param string $resourceType
     * @return ProcessorInterface
     * @throws NoSuchEntityException
     */
    public function get(string $resourceType): ProcessorInterface
    {
        $processor = $this->processorList[$resourceType] ?? null;
        if (!$processor) {
            throw new NoSuchEntityException(__('Resource with type = "%1" does not exist.', $resourceType));
        }

        return $processor;
    }
}
