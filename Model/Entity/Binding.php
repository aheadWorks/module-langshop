<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity;

use Magento\Framework\Model\AbstractModel;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding as BindingResource;

class Binding extends AbstractModel implements ResourceBindingInterface
{
    /**
     * Model construct that should be used for object initialization
     */
    protected function _construct(): void
    {
        $this->_init(BindingResource::class);
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
     * Set resource type
     *
     * @param string $resourceType
     * @return $this
     */
    public function setResourceType(string $resourceType): self
    {
        return $this->setData(self::RESOURCE_TYPE, $resourceType);
    }

    /**
     * Get original resource ID
     *
     * @return int
     */
    public function getOriginalResourceId(): int
    {
        return (int)$this->getData(self::ORIGINAL_RESOURCE_ID);
    }

    /**
     * Set original resource ID
     *
     * @param int $resourceId
     * @return $this
     */
    public function setOriginalResourceId(int $resourceId): self
    {
        return $this->setData(self::ORIGINAL_RESOURCE_ID, $resourceId);
    }

    /**
     * Get translated resource ID
     *
     * @return int
     */
    public function getTranslatedResourceId(): int
    {
        return (int)$this->getData(self::TRANSLATED_RESOURCE_ID);
    }

    /**
     * Set original resource ID
     *
     * @return $this
     */
    public function setTranslatedResourceId(int $resourceId): self
    {
        return $this->setData(self::TRANSLATED_RESOURCE_ID, $resourceId);
    }

    /**
     * Get store ID
     *
     * @return int
     */
    public function getStoreId(): int
    {
        return (int)$this->getData(self::STORE_ID);
    }

    /**
     * Set store ID
     *
     * @return $this
     */
    public function setStoreId(int $storeId): self
    {
        return $this->setData(self::STORE_ID, $storeId);
    }
}
