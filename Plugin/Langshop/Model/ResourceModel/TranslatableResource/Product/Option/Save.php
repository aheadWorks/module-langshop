<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\Option;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product as ProductResource;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Product\Option as OptionValidation;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NoSuchEntityException;

class Save
{
    /**
     * Field to process
     */
    private const KEY_FIELD = 'options';

    /**
     * @var OptionValidation
     */
    private OptionValidation $optionValidation;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param OptionValidation $optionValidation
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        OptionValidation $optionValidation,
        ResourceConnection $resourceConnection
    ) {
        $this->optionValidation = $optionValidation;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Validates options before saving
     *
     * @param ProductResource $productResource
     * @param Product $product
     * @return Product[]
     * @throws NoSuchEntityException
     */
    public function beforeSave(
        ProductResource $productResource,
        Product $product
    ): array {
        $options = $product->getData(self::KEY_FIELD);
        if (is_array($options) && $options) {
            foreach ($options as $optionId => $title) {
                $this->optionValidation->validate((int) $optionId, (int) $product->getId());
            }
        }

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
        $options = $product->getData(self::KEY_FIELD);
        if (is_array($options) && $options) {
            $toInsert = [];
            foreach ($options as $optionId => $title) {
                $toInsert[] = [
                    'option_id' => $optionId,
                    'store_id' => $product->getStoreId(),
                    'title' => $title
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
