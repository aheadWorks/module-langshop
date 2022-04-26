<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Field;

use Magento\Framework\DataObject;
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
     * @throws LocalizedException
     */
    public function process(DataObject $object, array $data): array
    {
        foreach ($this->processorList as $processor) {
            if (!$processor instanceof ProcessorInterface) {
                throw new LocalizedException(
                    __('Data processor must implement %1', ProcessorInterface::class)
                );
            }
            $data = $processor->process($object, $data);
        }

        return $data;
    }
}
