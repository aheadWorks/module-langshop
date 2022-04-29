<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Field;

class ProcessorPool
{
    /**
     * @var ProcessorInterface[]
     */
    private array $processors;

    /**
     * @param ProcessorInterface[] $processors
     */
    public function __construct(
        array $processors
    ) {
        $this->processors = $processors;
    }

    /**
     * @return ProcessorInterface[]
     */
    public function get(): array
    {
        return $this->processors;
    }
}
