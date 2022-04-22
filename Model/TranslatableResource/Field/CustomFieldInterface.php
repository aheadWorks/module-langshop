<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Field;

use Magento\Framework\DataObject;

interface CustomFieldInterface
{
    /**
     * Return field data
     *
     * @param DataObject $object
     * @param string $fieldCode
     * @return mixed
     */
    public function getData(DataObject $object, string $fieldCode);
}
