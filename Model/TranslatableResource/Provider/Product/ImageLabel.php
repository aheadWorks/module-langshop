<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Provider\Product;

use Aheadworks\Langshop\Model\TranslatableResource\Provider\Product\Metadata as ProductMetadata;
use Exception;
use Magento\Catalog\Model\ResourceModel\Product\Gallery as ProductGallery;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\Store;

class ImageLabel
{
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var ProductMetadata
     */
    private ProductMetadata $productMetadata;

    /**
     * @param ResourceConnection $resourceConnection
     * @param ProductMetadata $productMetadata
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        ProductMetadata $productMetadata
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->productMetadata = $productMetadata;
    }

    /**
     * Get gallery image labels
     *
     * @param int[] $productIds
     * @param int $storeId
     * @return array
     * @throws Exception
     */
    public function get(array $productIds, int $storeId = Store::DEFAULT_STORE_ID): array
    {
        $connection = $this->resourceConnection->getConnection();
        $linkField = $this->productMetadata->getLinkField();

        $select = $connection->select()
            ->from(
                ['value_to_entity' => $this->resourceConnection->getTableName(
                    ProductGallery::GALLERY_VALUE_TO_ENTITY_TABLE)
                ]
            )
            ->joinLeft(
                ['value' => $this->resourceConnection->getTableName(ProductGallery::GALLERY_VALUE_TABLE)],
                sprintf('value_to_entity.value_id = value.value_id AND value.store_id = %s', $storeId),
                ['label', 'record_id']
            )
            ->where("value_to_entity.$linkField in (?)", $productIds);

        return $connection->fetchAssoc($select);
    }
}
