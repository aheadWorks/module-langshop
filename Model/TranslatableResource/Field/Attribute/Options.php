<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Checker\Field as FieldChecker;
use Aheadworks\Langshop\Model\TranslatableResource\Field\CustomFieldInterface;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class Options implements CustomFieldInterface
{
    /**
     * @var FieldChecker
     */
    private FieldChecker $fieldChecker;

    /**
     * @param FieldChecker $fieldChecker
     */
    public function __construct(
        FieldChecker $fieldChecker
    ) {
        $this->fieldChecker = $fieldChecker;
    }

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
        if ($this->fieldChecker->canContainOptions($fieldType)) {
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
     * @return array Format: array('<value>' => '<label>', ...)
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
