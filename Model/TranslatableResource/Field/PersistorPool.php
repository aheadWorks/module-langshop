<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Field;

class PersistorPool
{
    /**
     * @var PersistorInterface[]
     */
    private array $persistors;

    /**
     * @param PersistorInterface[] $persistors
     */
    public function __construct(
        array $persistors
    ) {
        $this->persistors = $persistors;
    }

    /**
     * @return PersistorInterface[]
     */
    public function get(): array
    {
        return $this->persistors;
    }
}
