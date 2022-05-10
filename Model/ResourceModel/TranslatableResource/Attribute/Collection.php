<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\PersistorPool;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection as AttributeCollection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection\Proxy as AttributeCollectionProxy;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;

class Collection extends AttributeCollectionProxy
{
    /**
     * @var AttributeCollectionFactory
     */
    private AttributeCollectionFactory $attributeCollectionFactory;

    /**
     * @var PersistorPool
     */
    private PersistorPool $persistorPool;

    /**
     * @var int
     */
    private int $storeId = Store::DEFAULT_STORE_ID;

    /**
     * @param AttributeCollectionFactory $attributeCollectionFactory
     * @param PersistorPool $persistorPool
     */
    public function __construct(
        AttributeCollectionFactory $attributeCollectionFactory,
        PersistorPool $persistorPool
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->persistorPool = $persistorPool;
    }

    /**
     * Get proxied instance
     *
     * @return AttributeCollection
     */
    public function _getSubject(): AttributeCollection
    {
        if (!isset($this->_subject)) {
            $this->_subject = $this->attributeCollectionFactory->create();
        }

        return $this->_subject;
    }

    /**
     * Set store scope
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): Collection
    {
        $this->storeId = $storeId;

        return $this;
    }

    /**
     * Retrieve store scope
     *
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->storeId;
    }

    /**
     * Retrieve collection items
     *
     * @return AbstractModel[]
     */
    public function getItems(): array
    {
        /** @var AbstractModel[] $items */
        $items = parent::getItems();

        if ($items) {
            foreach ($this->persistorPool->get() as $persistor) {
                $persistor->load($items, $this->getStoreId());
            }
        }

        return $items;
    }
}
