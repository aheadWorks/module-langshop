<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Processor\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\ProcessorInterface;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\DataObject;
use Magento\Store\Model\Store;

class Options implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param Attribute $attribute
     */
    public function process(DataObject $attribute, array $data): array
    {
        $options = $data['options'] ?? [];

        if (!empty($options)) {
            $optionText = [];
            $defaultAttribute = clone $attribute;
            $defaultAttribute->setStoreId(Store::DEFAULT_STORE_ID);
            $defaultValues = $defaultAttribute->getSource()->getAllOptions(false);
            $defaultValue = [];
            foreach ($defaultValues as $value) {
                $defaultValue[$value['value']] = $value['label'];
            }
            foreach ($options as $optionId => $label) {
                $optionText[$optionId] = [
                    // default value should be for correct saving
                    Store::DEFAULT_STORE_ID => $defaultValue[$optionId],
                    $attribute->getStoreId() => $label
                ];
            }
            $data['optiontext']['value'] = $optionText;
        }

        return $data;
    }
}
