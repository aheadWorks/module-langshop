<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection as AttributeCollection;
use Magento\Store\Model\Store;

class Collection extends AttributeCollection
{
    /**
     * @var int
     */
    private int $storeId = Store::DEFAULT_STORE_ID;

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
}
