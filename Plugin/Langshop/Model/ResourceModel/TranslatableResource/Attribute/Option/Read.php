<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Attribute\Option;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute\Collection as AttributeCollection;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Attribute\Option as OptionProvider;
use Magento\Eav\Model\Entity\Attribute;

class Read
{
    /**
     * The model fields to work with
     */
    private const KEY_OPTIONS = 'options';

    /**
     * @var OptionProvider
     */
    private OptionProvider $optionProvider;

    /**
     * @param OptionProvider $optionProvider
     */
    public function __construct(
        OptionProvider $optionProvider
    ) {
        $this->optionProvider = $optionProvider;
    }

    /**
     * Retrieves options for the attributes
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
            $options = $this->optionProvider->get(
                array_keys($attributes),
                $attributeCollection->getStoreId()
            );

            foreach ($options as $optionId => $option) {
                $attribute = $attributes[$option->getAttributeId()];

                $attribute->setData(self::KEY_OPTIONS, array_replace(
                    $attribute->getData(self::KEY_OPTIONS) ?? [],
                    [$optionId => $option->getValue()]
                ));
            }
        }

        return $attributes;
    }
}
