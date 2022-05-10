<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\PersistorInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Model\AbstractModel;

class StoreLabel implements PersistorInterface
{
    /**
     * Field to process
     */
    private const FIELD = 'store_label';

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
     * Retrieves store labels for the attributes
     *
     * @param AbstractModel[] $items
     * @param int $storeId
     * @return void
     */
    public function load(array $items, int $storeId): void
    {
        foreach ($items as $item) {
            $item->setData(self::FIELD, $item->getData('frontend_label'));
        }

        foreach ($this->getStoreLabels(array_keys($items), $storeId) as $storeLabel) {
            $items[$storeLabel['attribute_id']]->setData(self::FIELD, $storeLabel['value']);
        }
    }

    /**
     * Saves store labels for the attribute
     *
     * @param AbstractModel $item
     * @param int $storeId
     * @return void
     */
    public function save(AbstractModel $item, int $storeId): void
    {
        $storeLabel = $item->getData(self::FIELD);
        if ($storeLabel) {
            $storeLabelId = array_key_first(
                $this->getStoreLabels([$item->getId()], $storeId)
            );

            $this->resourceConnection->getConnection()->insertOnDuplicate(
                $this->getTableName(),
                [
                    'attribute_label_id' => $storeLabelId,
                    'attribute_id' => $item->getId(),
                    'store_id' => $storeId,
                    'value' => $storeLabel
                ]
            );
        }
    }

    /**
     * Retrieves the main table to work with
     *
     * @return string
     */
    private function getTableName(): string
    {
        return $this->resourceConnection->getConnection()->getTableName(
            'eav_attribute_label'
        );
    }

    /**
     * Retrieves store labels from database
     *
     * @param int[] $attributeIds
     * @param int $storeId
     * @return array
     */
    private function getStoreLabels(array $attributeIds, int $storeId): array
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from($this->getTableName())
            ->where('attribute_id in(?)', $attributeIds)
            ->where('store_id = ?', $storeId);

        return $connection->fetchAssoc($select);
    }
}
