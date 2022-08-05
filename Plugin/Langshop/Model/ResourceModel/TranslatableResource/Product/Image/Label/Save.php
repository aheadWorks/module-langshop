<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\Image\Label;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product as ProductResource;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Product\Image as ProductImageProvider;
use Magento\Catalog\Model\Product;

class Save
{
    /**
     * @var ProductImageProvider
     */
    private ProductImageProvider $imageProvider;

    /**
     * @param ProductImageProvider $imageProvider
     */
    public function __construct(
        ProductImageProvider $imageProvider
    ) {
        $this->imageProvider = $imageProvider;
    }

    /**
     * Save image labels
     *
     * @param ProductResource $productResource
     * @param Product $product
     * @return Product[]
     */
    public function beforeSave(
        ProductResource $productResource,
        Product $product
    ): array {
        $images = $this->imageProvider->getImages([$product->getEntityId()], $product->getStoreId());
        $values = $this->imageProvider->getMediaGalleryValues([$product->getEntityId()], $product->getStoreId());
        $toInsert = [];
        $connection = $productResource->getConnection();
        $table = $productResource->getTable('catalog_product_entity_media_gallery_value');

        foreach ($images as $id => $image) {
            $label = $product->getData($image['attribute_code'] . '_label');
            if ($label) {
                $value = $values[$id];
                $value['label'] = $label;
                $toInsert[] = $value;
            } elseif ($label === false) {
                $where = [
                    'store_id = ?' => $product->getStoreId(),
                    'value_id = ?' => $image['value_id']
                ];
                $connection->delete($table, $where);
            }
        }

        if ($toInsert) {
            $connection->insertOnDuplicate(
                $table,
                $toInsert
            );
        }

        return [$product];
    }
}
