<?php
namespace Aheadworks\Langshop\Model\Schema\Processor;

use Aheadworks\Langshop\Api\Data\SchemaInterface;
use Aheadworks\Langshop\Model\Schema\ProcessorInterface;
use Magento\Framework\Exception\LocalizedException;

class Composite implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private array $processorList;

    /**
     * @param ProcessorInterface[] $processorList
     */
    public function __construct(
        array $processorList = []
    ) {
        $this->processorList = $processorList;
    }

    /**
     * @inheritDoc
     */
    public function process(SchemaInterface $schema): void
    {
        foreach ($this->processorList as $processor) {
            if (!$processor instanceof ProcessorInterface) {
                throw new LocalizedException(
                    __('Schema processor must implement %1', ProcessorInterface::class)
                );
            }
            $processor->process($schema);
        }
    }
}
