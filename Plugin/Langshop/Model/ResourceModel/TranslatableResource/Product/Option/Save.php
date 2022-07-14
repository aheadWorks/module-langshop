<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\Option;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product as ProductResource;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Option;
use Magento\Framework\App\ResourceConnection;

class Save
{
    /**
     * The model fields to work with
     */
    private const KEY_OPTIONS = 'options';

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
     * Validates options before saving
     *
     * @param ProductResource $productResource
     * @param Product $product
     * @return Product[]
     */
    public function beforeSave(
        ProductResource $productResource,
        Product $product
    ): array {
        return [
            // @see \Magento\Catalog\Model\Product\Option\SaveHandler
            $product->setData('options_saved', true)
        ];
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
        $options = $product->getData(self::KEY_OPTIONS);
        if (is_array($options) && $options) {
            $toInsert = [];
            /** @var Option $option */
            foreach ($options as $option) {
                $toInsert[] = [
                    'option_id' => $option->getOptionId(),
                    'store_id' => $product->getStoreId(),
                    'title' => $option->getTitle()
                ];
            }

            $this->resourceConnection->getConnection()->insertOnDuplicate(
                $this->resourceConnection->getTableName('catalog_product_option_title'),
                $toInsert
            );
        }

        return $result;
    }
}
