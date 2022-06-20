<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Attribute\Swatch;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute\Collection as AttributeCollection;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Attribute\Swatch as SwatchProvider;
use Magento\Eav\Model\Entity\Attribute;

class Read
{
    /**
     * The model fields to work with
     */
    private const KEY_SWATCHES = 'swatches';

    /**
     * @var SwatchProvider
     */
    private SwatchProvider $swatchProvider;

    /**
     * @param SwatchProvider $swatchProvider
     */
    public function __construct(
        SwatchProvider $swatchProvider
    ) {
        $this->swatchProvider = $swatchProvider;
    }

    /**
     * Retrieves swatches for the attributes
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
            $swatches = $this->swatchProvider->get(
                array_keys($attributes),
                $attributeCollection->getStoreId()
            );

            foreach ($swatches as $optionId => $swatch) {
                $attribute = $attributes[$swatch->getAttributeId()];

                $attribute->setData(self::KEY_SWATCHES, array_replace(
                    $attribute->getData(self::KEY_SWATCHES) ?? [],
                    [$optionId => $swatch->getValue()]
                ));
            }
        }

        return $attributes;
    }
}
