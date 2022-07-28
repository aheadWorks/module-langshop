<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\Option\Value;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product as ProductResource;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ResourceConnection;

class Save
{
    /**
     * The model fields to work with
     */
    private const KEY_OPTIONS_VALUES = 'options_values';

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
     * Saves options for the product
     *
     * @param ProductResource $productResource
     * @param ProductResource $result
     * @param Product $product
     * @return ProductResource
     */
    public function afterSave(
        ProductResource $productResource,
        ProductResource $result,
        Product $product
    ): ProductResource {
        $values = $product->getData(self::KEY_OPTIONS_VALUES);
        if (is_array($values)) {
            $titleTable = $this->resourceConnection->getTableName('catalog_product_option_type_title');
            $storeId = $product->getStoreId();
            $items = [];

            foreach ($values as $id => $title) {
                if ($title) {
                    $items[] = [
                        'option_type_id' => $id,
                        'title' => $title
                    ];
                } else {
                    $where = [
                        'option_type_id = ?' => $id,
                        'store_id = ?' => $storeId,
                    ];
                    $this->resourceConnection->getConnection()->delete($titleTable, $where);
                }
            }

            $valuesIds = $this->getOptionIdsByStoreId($storeId);
            $newItems = [];
            $oldItems = [];

            foreach ($items as $item) {
                $optionTypeId = $item['option_type_id'];
                $optionTypeTitleId = $valuesIds[$optionTypeId] ?? null;
                if ($optionTypeTitleId) {
                    $item['option_type_title_id'] = $optionTypeTitleId;
                    $oldItems[] = $item;
                } else {
                    $newItems[] = [
                        'option_type_id' => $optionTypeId,
                        'title' => $item['title'],
                        'store_id' => $storeId
                    ];
                }
            }

            if (!empty($oldItems)) {
                $this->resourceConnection->getConnection()->insertOnDuplicate(
                    $titleTable,
                    $oldItems
                );
            }

            if (!empty($newItems)) {
                $this->resourceConnection->getConnection()->insertMultiple(
                    $titleTable,
                    $newItems
                );
            }
        }

        return $result;
    }

    /**
     * Get option ids by story id
     *
     * @param int $storeId
     * @return array
     */
    private function getOptionIdsByStoreId(int $storeId): array
    {
        $titleTable = $this->resourceConnection->getTableName('catalog_product_option_type_title');
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()->from(
            $titleTable,
            ['option_type_title_id', 'option_type_id']
        )->where(
            'store_id = ?',
            $storeId
        );
        $values = $connection->fetchAll($select);

        $result = [];
        foreach ($values as $value) {
            $result[$value['option_type_id']] = $value['option_type_title_id'];
        }

        return $result;
    }
}
