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
     * @inheritDoc
     */
    public function setStatusId(int $statusId): StatusInterface
    {
        return $this->setData(self::STATUS_ID, $statusId);
    }

    /**
     * @inheritDoc
     */
    public function getStatusId(): int
    {
        return (int) $this->getData(self::STATUS_ID);
    }

    /**
     * @inheritDoc
     */
    public function setResourceType(string $resourceType): StatusInterface
    {
        return $this->setData(self::RESOURCE_TYPE, $resourceType);
    }

    /**
     * @inheritDoc
     */
    public function getResourceType(): string
    {
        return $this->getData(self::RESOURCE_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setResourceId(int $resourceId): StatusInterface
    {
        return $this->setData(self::RESOURCE_ID, $resourceId);
    }

    /**
     * @inheritDoc
     */
    public function getResourceId(): int
    {
        return (int) $this->getData(self::RESOURCE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setStoreId(int $storeId): StatusInterface
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @inheritDoc
     */
    public function getStoreId(): int
    {
        return (int) $this->getData(self::STORE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $status): StatusInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return (int) $this->getData(self::STATUS);
    }
}
