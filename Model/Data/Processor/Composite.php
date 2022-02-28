<?php
namespace Aheadworks\Langshop\Model\Data\Processor;

use Magento\Framework\Exception\ConfigurationMismatchException;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;

class Composite implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $processorList;

    /**
     * @param ProcessorInterface[] $processorList
     */
    public function __construct(array $processorList = [])
    {
        $this->processorList = $processorList;
    }

    /**
     * {@inheritdoc}
     */
    public function process($data)
    {
        foreach ($this->processorList as $processor) {
            if (!$processor instanceof ProcessorInterface) {
                throw new ConfigurationMismatchException(
                    __('Data processor must implement %1', ProcessorInterface::class)
                );
            }
            $data = $processor->process($data);
        }
        return $data;
    }
}
