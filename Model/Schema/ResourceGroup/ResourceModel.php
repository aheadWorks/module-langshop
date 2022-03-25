<?php
namespace Aheadworks\Langshop\Model\Schema\ResourceGroup;

use Aheadworks\Langshop\Api\Data\Schema\ResourceGroup\ResourceInterface;
use Magento\Framework\Model\AbstractModel;

class ResourceModel extends AbstractModel implements ResourceInterface
{
    /**
     * @inheritDoc
     */
    public function setResource($resource)
    {
        return $this->setData(self::RESOURCE, $resource);
    }

    /**
     * @inheritDoc
     */
    public function getResource()
    {
        return $this->getData(self::RESOURCE);
    }

    /**
     * @inheritDoc
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * @inheritDoc
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
}
