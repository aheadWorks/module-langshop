<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource;

use Magento\Framework\DB\Select;

trait CatalogCollectionTrait
{
    /**
     * Do not override store-specific attribute values by default ones
     *
     * @param string $table
     * @param int[]|int $attributeIds
     * @return Select
     */
    protected function _getLoadAttributesSelect($table, $attributeIds = [])
    {
        $select = parent::_getLoadAttributesSelect($table, $attributeIds);

        $storeId = $this->getStoreId();
        if ($storeId) {
            $select->where('t_d.store_id = ?', $storeId);
        }

        return $select;
    }
}
