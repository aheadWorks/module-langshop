<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Provider\Attribute;

use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\Store;

class StoreLabel
{
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Retrieves store labels for particular attributes
     *
     * @param int[] $attributeIds
     * @param int|null $storeId
     * @return array
     */
    public function get(array $attributeIds, ?int $storeId = Store::DEFAULT_STORE_ID): array
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('eav_attribute_label');

        $select = $connection->select()
            ->from($tableName)
            ->where('attribute_id in(?)', $attributeIds)
            ->where('store_id = ?', $storeId);

        return $connection->fetchAssoc($select);
    }
}
