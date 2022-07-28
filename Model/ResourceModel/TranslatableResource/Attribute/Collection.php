<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionInterface;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionTrait;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection as AttributeCollection;

class Collection extends AttributeCollection implements CollectionInterface
{
    use CollectionTrait;

    /**
     * Init select
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this
            ->getSelect()
            ->joinLeft(
                ['cea' => $this->getTable('catalog_eav_attribute')],
                'main_table.attribute_id = cea.attribute_id',
                ['additional_data']
            );
        return $this->addFilterToMap('attribute_id', 'main_table.attribute_id');
    }
}
