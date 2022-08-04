<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Attribute\StoreLabel;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute as AttributeResource;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Attribute\StoreLabel as StoreLabelProvider;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\App\ResourceConnection;

class Save
{
    /**
     * The model fields to work with
     */
    private const KEY_STORE_LABEL = 'store_label';
    private const KEY_STORE_ID = 'store_id';

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var StoreLabelProvider
     */
    private StoreLabelProvider $storeLabelProvider;

    /**
     * @param ResourceConnection $resourceConnection
     * @param StoreLabelProvider $storeLabelProvider
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        StoreLabelProvider $storeLabelProvider
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->storeLabelProvider = $storeLabelProvider;
    }

    /**
     * Saves store label for the attribute
     *
     * @param AttributeResource $attributeResource
     * @param AttributeResource $result
     * @param Attribute $attribute
     * @return AttributeResource
     */
    public function afterSave(
        AttributeResource $attributeResource,
        AttributeResource $result,
        Attribute $attribute
    ): AttributeResource {
        $storeLabel = $attribute->getData(self::KEY_STORE_LABEL);
        $storeId = (int) $attribute->getData(self::KEY_STORE_ID);

        if ($storeLabel) {
            $storeLabelId = array_key_first(
                $this->storeLabelProvider->get([$attribute->getId()], $storeId)
            );

            $this->resourceConnection->getConnection()->insertOnDuplicate(
                $this->resourceConnection->getTableName('eav_attribute_label'),
                [
                    'attribute_label_id' => $storeLabelId,
                    'attribute_id' => $attribute->getId(),
                    'store_id' => $storeId,
                    'value' => $storeLabel
                ]
            );
        } elseif ($storeLabel === false) {
            $storeLabelId = array_key_first(
                $this->storeLabelProvider->get([$attribute->getId()], $storeId)
            );
            $this->resourceConnection->getConnection()->delete(
                $this->resourceConnection->getTableName('eav_attribute_label'),
                'attribute_label_id = ' . $storeLabelId
            );
        }

        return $result;
    }
}
