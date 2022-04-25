<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Field;

use Magento\Framework\DataObject;

class DefaultField implements CustomFieldInterface
{
    /**
     * @inheritDoc
     */
    public function getData(DataObject $object, string $fieldCode)
    {
        return $object->getData($fieldCode);
    }
}
