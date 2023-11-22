<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\UrlKey;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product as ProductResource;

class Save
{
    /**
     * Set url_key attributes to avoid url rewrites logic
     *
     * @param ProductResource $productResource
     * @param Product $product
     * @return Product[]
     */
    public function beforeSave(
        ProductResource $productResource,
        Product $product
    ): array {
        $entityId = $product->getEntityId();
        if ($entityId) {
            $urlKey = $productResource->getAttributeRawValue(
                $entityId,
                ProductAttributeInterface::CODE_SEO_FIELD_URL_KEY,
                $product->getStoreId()
            );
            if ($urlKey && is_string($urlKey)) {
                $product
                    ->setData(ProductAttributeInterface::CODE_SEO_FIELD_URL_KEY, $urlKey)
                    ->setOrigData(ProductAttributeInterface::CODE_SEO_FIELD_URL_KEY, $urlKey);
            }
        }

        return [$product];
    }
}
