<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\Image\Label;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product as ProductResource;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Product\ImageLabel as ImageLabelProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Product\Metadata as ProductMetadata;
use Exception;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Gallery as ProductGallery;
use Magento\Framework\App\ResourceConnection;

class Save
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
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var ProductMetadata
     */
    private ProductMetadata $productMetadata;

    /**
     * @param ImageLabelProvider $imageLabelProvider
     * @param ResourceConnection $resourceConnection
     * @param ProductMetadata $productMetadata
     */
    public function __construct(
        ImageLabelProvider $imageLabelProvider,
        ResourceConnection $resourceConnection,
        ProductMetadata $productMetadata
    ) {
        $this->imageLabelProvider = $imageLabelProvider;
        $this->resourceConnection = $resourceConnection;
        $this->productMetadata = $productMetadata;
    }

    /**
     * Saves image labels
     *
     * @param ProductResource $productResource
     * @param Product $product
     * @return Product[]
     * @throws Exception
     */
    public function beforeSave(
        ProductResource $productResource,
        Product $product
    ): array {
        $imageLabels = $product->getData(self::KEY_IMAGE_LABELS);
        if (is_array($imageLabels) && $imageLabels) {
            $storeId = $product->getStoreId();
            $linkField = $this->productMetadata->getLinkField();
            $existingLabels = $this->imageLabelProvider->get([$product->getId()], $storeId);
            $toInsert = $toDelete = [];

            foreach ($imageLabels as $imageId => $imageLabel) {
                $existingLabel = $existingLabels[$imageId];

                if ($imageLabel) {
                    $toInsert[] = [
                        'value_id' => $imageId,
                        'store_id' => $storeId,
                        'label' => $imageLabel,
                        'record_id' => $existingLabel['record_id'],
                        $linkField => $existingLabel[$linkField]
                    ];
                } elseif ($existingLabel['record_id']) {
                    $toDelete[] = $existingLabel['record_id'];
                }
            }

            $this->insertLabels($toInsert);
            $this->deleteLabels($toDelete);
        }

        return [$product];
    }

    /**
     * Inserts image labels to the table
     *
     * @param array $toInsert
     */
    private function insertLabels(array $toInsert): void
    {
        if ($toInsert) {
            $this->resourceConnection->getConnection()->insertOnDuplicate(
                $this->resourceConnection->getTableName(ProductGallery::GALLERY_VALUE_TABLE),
                $toInsert
            );
        }
    }

    /**
     * Deletes image labels if this is required
     *
     * @param int[] $toDelete
     */
    private function deleteLabels(array $toDelete): void
    {
        if ($toDelete) {
            $this->resourceConnection->getConnection()->delete(
                $this->resourceConnection->getTableName(ProductGallery::GALLERY_VALUE_TABLE),
                sprintf('record_id in (%s)', implode(',', $toDelete))
            );
        }
    }
}
