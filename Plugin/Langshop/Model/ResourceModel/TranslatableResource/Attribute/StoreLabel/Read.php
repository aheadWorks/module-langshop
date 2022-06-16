<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Attribute\StoreLabel;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute\Collection as AttributeCollection;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Attribute\StoreLabel as StoreLabelProvider;
use Magento\Eav\Model\Entity\Attribute;

class Read
{
    /**
     * The model fields to work with
     */
    private const KEY_STORE_LABEL = 'store_label';

    /**
     * @var StoreLabelProvider
     */
    private StoreLabelProvider $storeLabelProvider;

    /**
     * @param StoreLabelProvider $storeLabelProvider
     */
    public function __construct(
        StoreLabelProvider $storeLabelProvider
    ) {
        $this->storeLabelProvider = $storeLabelProvider;
    }

    /**
     * Retrieves store labels for the attributes
     *
     * @param AttributeCollection $attributeCollection
     * @param Attribute[] $attributes
     * @return Attribute[]
     */
    public function afterGetItems(
        AttributeCollection $attributeCollection,
        array $attributes
    ): array {
        if ($attributes) {
            foreach ($attributes as $attribute) {
                $attribute->setData(self::KEY_STORE_LABEL, $attribute->getDefaultFrontendLabel());
            }

            $storeLabels = $this->storeLabelProvider->get(
                array_keys($attributes),
                $attributeCollection->getStoreId()
            );

            foreach ($storeLabels as $storeLabel) {
                $attributes[$storeLabel['attribute_id']]->setData(self::KEY_STORE_LABEL, $storeLabel['value']);
            }
        }

        return $attributes;
    }
}
