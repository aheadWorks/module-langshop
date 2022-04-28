<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\ProcessorInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Model\AbstractModel;

class StoreLabel implements ProcessorInterface
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
     * Retrieves store label for the attributes
     *
     * @param AbstractModel[] $items
     * @param int $storeId
     * @return void
     */
    public function load(array $items, int $storeId): void
    {
        $storeLabels = $this->getStoreLabels(array_keys($items), $storeId);

        foreach ($items as $item) {
            $item->setData('store_label', $storeLabels[$item->getId()] ?? $item->getData('frontend_label'));
        }
    }

    /**
     * @param int[] $attributeIds
     * @param int $storeId
     * @return array
     */
    private function getStoreLabels(array $attributeIds, int $storeId): array
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from($connection->getTableName('eav_attribute_label'), ['attribute_id', 'value'])
            ->where('attribute_id in(?)', $attributeIds)
            ->where('store_id = ?', $storeId);

        return $connection->fetchPairs($select);
    }
}
