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
    private ResourceConnection $resource;

    /**
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
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
        $storeLabels = $this->getStoreLabels(array_keys($items), $storeId);

        foreach ($items as $item) {
            $item->setData('store_label', $storeLabels[$item->getId()] ?? $item->getData('frontend_label'));
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
        $storeLabel = $item->getData('store_label');
        if ($storeLabel) {
            $this->resource->getConnection()->insertOnDuplicate(
                $this->getTableName(),
                [
                    'attribute_id' => $item->getId(),
                    'store_id' => $storeId,
                    'value' => $item->getData('store_label')
                ],
                ['value']
            );
        }
    }

    /**
     * @return string
     */
    private function getTableName(): string
    {
        return $this->resource->getConnection()->getTableName(
            'eav_attribute_label'
        );
    }

    /**
     * @param int[] $attributeIds
     * @param int $storeId
     * @return array
     */
    private function getStoreLabels(array $attributeIds, int $storeId): array
    {
        $connection = $this->resource->getConnection();
        $select = $connection->select()
            ->from($this->getTableName(), ['attribute_id', 'value'])
            ->where('attribute_id in(?)', $attributeIds)
            ->where('store_id = ?', $storeId);

        return $connection->fetchPairs($select);
    }
}
