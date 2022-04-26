<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\CustomFieldInterface;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\DataObject;

class StoreLabel implements CustomFieldInterface
{
    /**
     * @inheritDoc
     * @param Attribute $attribute
     */
    public function getData(DataObject $object, string $fieldCode)
    {
        return $object->getStoreLabel($object->getStoreId());
    }
}
