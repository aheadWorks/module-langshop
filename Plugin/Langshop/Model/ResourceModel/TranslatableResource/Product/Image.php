<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product\Collection as ProductCollection;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Media\Config as MediaConfig;

class Image
{
    /**
     * The model fields to work with
     */
    private const KEY_IMAGE = 'image';
    private const KEY_IMAGE_CONVERTED = 'image_converted';

    /**
     * @var MediaConfig
     */
    private MediaConfig $mediaConfig;

    /**
     * @param MediaConfig $mediaConfig
     */
    public function __construct(
        MediaConfig $mediaConfig
    ) {
        $this->mediaConfig = $mediaConfig;
    }

    /**
     * Retrieves full path for the product image
     *
     * @param ProductCollection $productCollection
     * @param Product $product
     * @return Product
     */
    public function afterGetItemById(
        ProductCollection $productCollection,
        Product $product
    ): Product {
        $image = $product->getImage();
        $isConverted = $product->getData(self::KEY_IMAGE_CONVERTED);

        if ($image && !$isConverted) {
            $product
                ->setData(self::KEY_IMAGE, $this->mediaConfig->getMediaUrl($image))
                ->setData(self::KEY_IMAGE_CONVERTED, true);
        }

        return $product;
    }
}
