<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\Image\Label;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product\Collection as ProductCollection;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Product\Image as ProductImageProvider;
use Magento\Catalog\Model\Product;

class Read
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
     * Retrieves image labels for the products
     *
     * @param ProductCollection $productCollection
     * @param Product[] $products
     * @return Product[]
     */
    public function afterGetItems(
        ProductCollection $productCollection,
        array $products
    ): array {
        if ($products) {
            $images = $this->imageProvider->getImages(
                array_keys($products),
                $productCollection->getStoreId()
            );

            foreach ($images as $image) {
                $products[$image['entity_id']]->setData(
                    $image['attribute_code'] . '_label',
                    $image['label']
                );
            }
        }

        return $products;
    }
}
