<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product;

use Aheadworks\Langshop\Model\TranslatableResource\Field\PersistorPool;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\Collection\Proxy as ProductCollectionProxy;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;

class Collection extends ProductCollectionProxy
{
    /**
     * @var ProductCollectionFactory
     */
    private ProductCollectionFactory $productCollectionFactory;

    /**
     * @var PersistorPool
     */
    private PersistorPool $persistorPool;

    /**
     * @var int
     */
    private int $storeId = Store::DEFAULT_STORE_ID;

    /**
     * @param ProductCollectionFactory $productCollectionFactory
     * @param PersistorPool $persistorPool
     */
    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        PersistorPool $persistorPool
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->persistorPool = $persistorPool;
    }

    /**
     * Get proxied instance
     *
     * @return ProductCollection
     */
    public function _getSubject(): ProductCollection
    {
        // @phpstan-ignore-next-line
        if (!isset($this->_subject)) {
            $this->_subject = $this->productCollectionFactory->create();
        }

        return $this->_subject;
    }

    /**
     * Set store scope
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId): Collection
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
     * Get item by id
     *
     * @param int $id
     * @return AbstractModel
     */
    public function getItemById($id)
    {
        /** @var AbstractModel|null $item */
        $item = parent::getItemById($id);

        if ($item) {
            foreach ($this->persistorPool->get() as $persistor) {
                $persistor->load([$item], $this->getStoreId());
            }
        }

        return $item;
    }
}
