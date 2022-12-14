<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Field;

use Magento\Framework\Exception\LocalizedException;

class Collector implements CollectorInterface
{
    /**
     * @var CollectorInterface[]
     */
    private array $collectorList;

    /**
     * @param CollectorInterface[] $collectorList
     */
    public function __construct(
        array $collectorList = []
    ) {
        $this->collectorList = $collectorList;
    }

    /**
     * @inheritDoc
     */
    public function collect(array $fields = []): array
    {
        foreach ($this->collectorList as $collector) {
            if (!$collector instanceof CollectorInterface) {
                throw new LocalizedException(
                    __('Field collector must implement %1', CollectorInterface::class)
                );
            }
            $fields = $collector->collect($fields);
        }

        return $fields;
    }
}
