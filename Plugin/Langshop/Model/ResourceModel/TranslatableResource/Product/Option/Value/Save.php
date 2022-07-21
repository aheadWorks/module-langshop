<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\Option\Value;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product as ProductResource;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\Store;

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
            foreach ($values as $optionTypeId => $title) {
                $this->save($title, (int)$optionTypeId, $product->getStoreId());
            }
        }

        return $result;
    }

    /**
     * Save option value title
     *
     * @param string|null $title
     * @param int $optionTypeId
     * @param int $storeId
     */
    private function save($title, int $optionTypeId, int $storeId): void
    {
        $titleTable = $this->resourceConnection->getTableName('catalog_product_option_type_title');
        if ($title) {
            $existInCurrentStore = $this->getOptionIdFromOptionTable($titleTable, $optionTypeId, $storeId);

            if ($existInCurrentStore) {
                $where = [
                    'option_type_id = ?' => $optionTypeId,
                    'store_id = ?' => $storeId,
                ];
                $bind = ['title' => $title];
                $this->resourceConnection->getConnection()->update($titleTable, $bind, $where);
            } else {
                $existInDefaultStore = $this->getOptionIdFromOptionTable(
                    $titleTable,
                    $optionTypeId,
                    Store::DEFAULT_STORE_ID
                );
                // we should insert record into not default store only of if it does not exist in default store
                if (($storeId === Store::DEFAULT_STORE_ID && !$existInDefaultStore)
                    || ($storeId !== Store::DEFAULT_STORE_ID && !$existInCurrentStore)
                ) {
                    $bind = [
                        'option_type_id' => $optionTypeId,
                        'store_id' => $storeId,
                        'title' => $title,
                    ];
                    $this->resourceConnection->getConnection()->insert($titleTable, $bind);
                }
            }
        } else if ($storeId && $optionTypeId && $storeId > Store::DEFAULT_STORE_ID) {
            $where = [
                'option_type_id = ?' => $optionTypeId,
                'store_id = ?' => $storeId,
            ];
            $this->resourceConnection->getConnection()->delete($titleTable, $where);
        }
    }

    /**
     * Get first col from first row for option table
     *
     * @param string $tableName
     * @param int $optionId
     * @param int $storeId
     * @return string
     */
    private function getOptionIdFromOptionTable(string $tableName, int $optionId, int $storeId): string
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()->from(
            $tableName,
            ['option_type_id']
        )->where(
            'option_type_id = ?',
            $optionId
        )->where(
            'store_id = ?',
            $storeId
        );

        return (string)$connection->fetchOne($select);
    }
}
