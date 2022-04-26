<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Processor\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\ProcessorInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Swatch as SwatchProvider;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\DataObject;
use Magento\Store\Model\Store;

class Swatches implements ProcessorInterface
{
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
     * @inheritDoc
     * @param Attribute $attribute
     */
    public function process(DataObject $attribute, array $data): array
    {
        $swatches = $data['swatches'] ?? [];

        if (!empty($swatches)) {
            $defaultValues = $this->swatchProvider->getStoreOptionValues($attribute, Store::DEFAULT_STORE_ID);
            $value = [];
            foreach ($swatches as $optionId => $label) {
                $value[$optionId] = [
                    // default value should be for correct saving
                    Store::DEFAULT_STORE_ID => $defaultValues[$optionId],
                    $attribute->getStoreId() => $label
                ];
            }
            $data['swatchtext']['value'] = $value;
        }

        return $data;
    }
}
