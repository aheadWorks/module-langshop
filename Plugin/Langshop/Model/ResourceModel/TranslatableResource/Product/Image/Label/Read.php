<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\Image\Label;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product\Collection as ProductCollection;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Product\ImageLabel as ImageLabelProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Product\Metadata as ProductMetadata;
use Exception;
use Magento\Catalog\Model\Product;

class Read
{
    /**
     * The model fields to work with
     */
    private const KEY_IMAGE_LABELS = 'image_labels';

    /**
     * @var ImageLabelProvider
     */
    private ImageLabelProvider $imageLabelProvider;

    /**
     * @var ProductMetadata
     */
    private ProductMetadata $productMetadata;

    /**
     * @param ImageLabelProvider $imageLabelProvider
     * @param ProductMetadata $productMetadata
     */
    public function __construct(
        ImageLabelProvider $imageLabelProvider,
        ProductMetadata $productMetadata
    ) {
        $this->imageLabelProvider = $imageLabelProvider;
        $this->productMetadata = $productMetadata;
    }

    /**
     * Retrieves image labels for the products
     *
     * @param ProductCollection $productCollection
     * @param Product[] $products
     * @return Product[]
     * @throws Exception
     */
    public function afterGetItems(
        ProductCollection $productCollection,
        array $products
    ): array {
        if ($products) {
            $imageLabels = $this->imageLabelProvider->get(
                array_keys($products),
                $productCollection->getStoreId()
            );
            $linkField = $this->productMetadata->getLinkField();

            foreach ($imageLabels as $imageId => $imageLabel) {
                $product = $products[$imageLabel[$linkField]];

                $product->setData(self::KEY_IMAGE_LABELS, array_replace(
                    $product->getData(self::KEY_IMAGE_LABELS) ?? [],
                    [$imageId => $imageLabel['label']]
                ));
            }
        }

        return $products;
    }
}
