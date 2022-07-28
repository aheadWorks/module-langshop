<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Attribute\FrontendInput;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute\Collection as AttributeCollection;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\Serialize\Serializer\Json;

class Read
{
    /**
     * @var Json
     */
    private Json $serializer;

    /**
     * @param Json $serializer
     */
    public function __construct(
        Json $serializer
    ) {
        $this->serializer = $serializer;
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
        foreach ($attributes as $attribute) {
            $additionalData = $attribute->getData('additional_data');
            if ($additionalData) {
                $additionalData = $this->serializer->unserialize($additionalData);
                if (isset($additionalData['swatch_input_type'])) {
                    $attribute->setFrontendInput($additionalData['swatch_input_type']);
                }
            }
        }

        return $attributes;
    }

    /**
     * Retrieves options for the attributes
     *
     * @param AttributeCollection $attributeCollection
     * @param Attribute $attribute
     * @param int $id
     * @return Attribute
     */
    public function afterGetItemById(
        AttributeCollection $attributeCollection,
        Attribute $attribute,
        int $id
    ): Attribute {
        $additionalData = $attribute->getData('additional_data');
        if ($additionalData) {
            $additionalData = $this->serializer->unserialize($additionalData);
            if (isset($additionalData['swatch_input_type'])) {
                $attribute->setFrontendInput($additionalData['swatch_input_type']);
            }
        }

        return $attribute;
    }
}
