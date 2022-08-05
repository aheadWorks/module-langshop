<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\TranslatableResource\Provider\Product;

use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\Store;

class Image
{
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var string[]
     */
    private array $attributeCodes = [
        'image',
        'small_image',
        'thumbnail'
    ];

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Get images
     *
     * @param int[] $productIds
     * @param int $storeId
     * @return array
     */
    public function getImages(array $productIds, int $storeId = Store::DEFAULT_STORE_ID): array
    {
        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()
            ->from(
                ['main' => $this->resourceConnection->getTableName('catalog_product_entity_varchar')]
            )->joinLeft(
                ['eava' => $this->resourceConnection->getTableName('eav_attribute')],
                'main.attribute_id = eava.attribute_id',
                ['attribute_code']
            )->joinLeft(
                ['mg' => $this->resourceConnection->getTableName('catalog_product_entity_media_gallery')],
                'main.value = mg.value',
                ['value_id']
            )->joinLeft(
                ['mgv' => $this->resourceConnection->getTableName('catalog_product_entity_media_gallery_value')],
                'mg.value_id = mgv.value_id AND main.store_id = mgv.store_id',
                ['label']
            )->where(
                'eava.attribute_code IN (?)',
                $this->attributeCodes
            )->where(
                'mgv.store_id = ?',
                $storeId
            )->where(
                'main.entity_id IN (?)',
                $productIds
            );

        return $connection->fetchAssoc($select);
    }

    /**
     * Get media gallery values
     *
     * @param array $productIds
     * @param int $storeId
     * @return array
     */
    public function getMediaGalleryValues(array $productIds, int $storeId = Store::DEFAULT_STORE_ID): array
    {
        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()
            ->from(
                ['main' => $this->resourceConnection->getTableName('catalog_product_entity_media_gallery_value')]
            )->where(
                'main.store_id = ?',
                $storeId
            )->where(
                'main.entity_id IN (?)',
                $productIds
            );

        return $connection->fetchAssoc($select);
    }
}
