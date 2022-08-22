<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Schema\ResourceGroup;

use Aheadworks\Langshop\Api\Data\Schema\ResourceGroup\ResourceInterface;
use Magento\Framework\DataObject;

class ResourceModel extends DataObject implements ResourceInterface
{
    /**
     * Constants for internal keys
     */
    private const RESOURCE = 'resource';
    private const SORT_ORDER = 'sort_order';

    /**
     * Set resource
     *
     * @param string $resource
     * @return $this
     */
    public function setResource(string $resource): ResourceInterface
    {
        return $this->setData(self::RESOURCE, $resource);
    }

    /**
     * Get resource
     *
     * @return string
     */
    public function getResource(): string
    {
        return $this->getData(self::RESOURCE);
    }

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder(int $sortOrder): ResourceInterface
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder(): int
    {
        return (int)$this->getData(self::SORT_ORDER);
    }
}
