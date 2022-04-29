<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\ProcessorPool;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection as AttributeCollection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection\Proxy as AttributeCollectionProxy;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;

class Collection extends AttributeCollectionProxy
{
    /**
     * @var AttributeCollectionFactory
     */
    private AttributeCollectionFactory $attributeCollectionFactory;

    /**
     * @var ProcessorPool
     */
    private ProcessorPool $processorPool;

    /**
     * @var int
     */
    private int $storeId = Store::DEFAULT_STORE_ID;

    /**
     * @param AttributeCollectionFactory $attributeCollectionFactory
     * @param ProcessorPool $processorPool
     */
    public function __construct(
        AttributeCollectionFactory $attributeCollectionFactory,
        ProcessorPool $processorPool
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->processorPool = $processorPool;
    }

    /**
     * @return AttributeCollection
     */
    public function _getSubject(): AttributeCollection
    {
        if (!$this->_subject) {
            $this->_subject = $this->attributeCollectionFactory->create();
        }

        return $this->_subject;
    }

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): Collection
    {
        $this->storeId = $storeId;

        return $this;
    }

    /**
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
            foreach ($this->processorPool->get() as $processor) {
                $processor->load($items, $this->getStoreId());
            }
        }

        return $items;
    }
}
