<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource;

use Magento\Store\Model\Store;

trait CollectionTrait
{
    /**
     * @var string
     */
    private string $resourceType = '';

    /**
     * @var int
     */
    protected $_storeId;

    /**
     * Set related resource type
     *
     * @param string $resourceType
     * @return $this
     */
    public function setResourceType(string $resourceType)
    {
        $this->resourceType = $resourceType;

        return $this;
    }

    /**
     * Retrieve related resource type
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    /**
     * Set store scope
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;

        return $this;
    }

    /**
     * Retrieve store scope
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeId ?: Store::DEFAULT_STORE_ID;
    }
}
