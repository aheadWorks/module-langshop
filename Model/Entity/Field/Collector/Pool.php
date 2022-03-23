<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Collector;

use Aheadworks\Langshop\Model\Entity\Field\CollectorInterface;
use Magento\Framework\Exception\LocalizedException;

class Pool
{
    /**
     * @var CollectorInterface[]
     */
    private $collectorList;

    /**
     * @param CollectorInterface[] $collectorList
     */
    public function __construct(
        array $collectorList = []
    ) {
        $this->collectorList = $collectorList;
    }

    /**
     * Get field collector
     *
     * @param string $resourceType
     * @return CollectorInterface
     * @throws LocalizedException
     */
    public function get($resourceType)
    {
        if (!isset($this->collectorList[$resourceType])) {
            throw new LocalizedException(__('No such collectors with resource type = %1', $resourceType));
        }

        return $this->collectorList[$resourceType];
    }
}
