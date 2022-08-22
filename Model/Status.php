<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model;

use Aheadworks\Langshop\Api\Data\StatusInterface;
use Aheadworks\Langshop\Model\ResourceModel\Status as StatusResource;
use Magento\Catalog\Model\AbstractModel;

class Status extends AbstractModel implements StatusInterface
{
    /**
     * Relation to the resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(StatusResource::class);
    }

    /**
     * Set status id
     *
     * @param int $statusId
     * @return $this
     */
    public function setStatusId(int $statusId): StatusInterface
    {
        return $this->setData(self::STATUS_ID, $statusId);
    }

    /**
     * Get status id
     *
     * @return int
     */
    public function getStatusId(): int
    {
        return (int) $this->getData(self::STATUS_ID);
    }

    /**
     * Set resource type
     *
     * @param string $resourceType
     * @return $this
     */
    public function setResourceType(string $resourceType): StatusInterface
    {
        return $this->setData(self::RESOURCE_TYPE, $resourceType);
    }

    /**
     * Get resource type
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return $this->getData(self::RESOURCE_TYPE);
    }

    /**
     * Set resource id
     *
     * @param int $resourceId
     * @return $this
     */
    public function setResourceId(int $resourceId): StatusInterface
    {
        return $this->setData(self::RESOURCE_ID, $resourceId);
    }

    /**
     * Get resource id
     *
     * @return int
     */
    public function getResourceId(): int
    {
        return (int) $this->getData(self::RESOURCE_ID);
    }

    /**
     * Set store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): StatusInterface
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Get store id
     *
     * @return int
     */
    public function getStoreId(): int
    {
        return (int) $this->getData(self::STORE_ID);
    }

    /**
     * Set status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): StatusInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return (int) $this->getData(self::STATUS);
    }
}
