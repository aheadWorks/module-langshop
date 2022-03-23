<?php
namespace Aheadworks\Langshop\Model\Entity\Field;

use Aheadworks\Langshop\Model\Entity\Field;
use Aheadworks\Langshop\Model\Entity\Field\Collector\Pool as CollectorPool;
use Magento\Framework\Exception\LocalizedException;

class Repository
{
    /**
     * @var CollectorPool
     */
    private $collectorPool;

    /**
     * @var array
     */
    private $fields;

    /**
     * @param CollectorPool $collectorPool
     */
    public function __construct(
        CollectorPool $collectorPool
    ) {
        $this->collectorPool = $collectorPool;
    }

    /**
     * Get fields by resource type
     *
     * @param string $resourceType
     * @return Field[]
     * @throws LocalizedException
     */
    public function get($resourceType)
    {
        $collector = $this->collectorPool->get($resourceType);
        $this->fields[$resourceType] = $collector->collect();

        return $this->fields[$resourceType];
    }
}
