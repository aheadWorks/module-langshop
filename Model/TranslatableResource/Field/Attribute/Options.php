<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\CustomFieldInterface;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class Options implements CustomFieldInterface
{
    /**
     * @inheritDoc
     * @param Attribute $attribute
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getData(DataObject $attribute, string $fieldCode)
    {
        $values = [];
        $fieldType = $attribute->getFrontendInput();
        if (in_array($fieldType, ['select', 'multiselect'])) {
            $options = $attribute->getSource()->getAllOptions(false);

            foreach ($options as $option) {
                if (!empty($option['value'])) {
                    $values = array_replace($values, $this->getPreparedValue($option['value'], $option['label']));
                }
            }
        }

        return $values;
    }

    /**
     * Get prepared value
     *
     * @param mixed $value
     * @param string $label
     * @return array
     * @throws LocalizedException
     */
    private function getPreparedValue($value, string $label): array
    {
        $values = [];
        if (is_array($value)) {
            foreach ($value as $item) {
                $values = array_replace($values, $this->getPreparedValue($item['value'], $item['label']));
            }
        } else {
            $values[$value] = $label;
        }

        return $values;
    }
}
