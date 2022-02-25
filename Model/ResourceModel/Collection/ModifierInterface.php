<?php
namespace Aheadworks\Langshop\Model\ResourceModel\Collection;

use Magento\Framework\DataObject;

interface ModifierInterface
{
    /**
     * Modify data of collection item
     *
     * @param DataObject $item
     * @return DataObject
     */
    public function modifyData($item);
}
