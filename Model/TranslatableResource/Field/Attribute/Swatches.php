<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Checker\Field as FieldChecker;
use Aheadworks\Langshop\Model\TranslatableResource\Field\CustomFieldInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Swatch as SwatchProvider;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory;
use Magento\Framework\DataObject;

class Swatches implements CustomFieldInterface
{
    /**
     * @var SwatchProvider
     */
    private SwatchProvider $swatchProvider;

    /**
     * @var FieldChecker
     */
    private FieldChecker $fieldChecker;

    /**
     * @var array
     */
    private array $values = [];

    /**
     * @param SwatchProvider $swatchProvider
     * @param FieldChecker $fieldChecker
     */
    public function __construct(
        SwatchProvider $swatchProvider,
        FieldChecker $fieldChecker
    ) {
        $this->swatchProvider = $swatchProvider;
        $this->fieldChecker = $fieldChecker;
    }

    /**
     * @inheritDoc
     * @param Attribute $attribute
     */
    public function getData(DataObject $attribute, string $fieldCode)
    {
        $storeId = $attribute->getStoreId();
        $attributeId = $attribute->getId();
        if (!isset($this->values[$attributeId][$storeId])) {
            $fieldType = $attribute->getFrontendInput();
            $this->values[$attributeId][$storeId] = $this->fieldChecker->canContainOptions($fieldType)
                ? $this->swatchProvider->getValues($attribute)
                : [];
        }

        return $this->values[$attributeId][$storeId];
    }
}
