<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Magento\Cms\Model\ResourceModel\Block\Grid;

use Magento\Cms\Model\ResourceModel\AbstractCollection;
use Magento\Store\Model\Store;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding as BindingResource;

class CollectionPlugin
{
    /**
     * @param BindingResource $bindingResource
     * @param string $resourceType
     * @param string $entityFieldName
     */
    public function __construct(
        private readonly BindingResource $bindingResource,
        private readonly string $resourceType = ResourceBindingInterface::CMS_BLOCK_RESOURCE_TYPE,
        private readonly string $entityFieldName = 'block_id'
    ) {
    }

    /**
     * Added custom filter
     *
     * @param AbstractCollection $subject
     * @param callable $proceed
     * @param $field
     * @param $condition
     * @return AbstractCollection
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundAddFieldToFilter(
        AbstractCollection $subject,
        callable $proceed,
        $field,
        $condition,
    ): AbstractCollection {
        if ($field == 'aw_ls_translation') {
            $subject->getSelect()
                ->joinInner(
                    ['binding_tbl' => $this->bindingResource->getTable(BindingResource::MAIN_TABLE_NAME)],
                    $this->getBindingTableJoinCondition(),
                    []
                );
            return $subject;
        }

        return $proceed($field, $condition);
    }

    /**
     * Get binding table join condition
     *
     * @return string
     */
    private function getBindingTableJoinCondition(): string
    {
        return sprintf(
            'main_table.%s = binding_tbl.%s AND resource_type = "%s" AND binding_tbl.store_id = %s',
            $this->entityFieldName,
            ResourceBindingInterface::ORIGINAL_RESOURCE_ID,
            $this->resourceType,
            Store::DEFAULT_STORE_ID
        );
    }
}
