<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Magento\Cms\Model\ResourceModel\Block\Grid;

use Magento\Cms\Model\ResourceModel\Block\Grid\Collection;
use Magento\Store\Model\Store;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding as BindingResource;

class CollectionPlugin
{
    /**
     * @param BindingResource $bindingResource
     */
    public function __construct(
        private readonly BindingResource $bindingResource
    ) {
    }

    /**
     * Added custom filter
     *
     * @param Collection $subject
     * @param callable $proceed
     * @param $field
     * @param $condition
     * @return Collection
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundAddFieldToFilter(
        Collection $subject,
        callable $proceed,
        $field,
        $condition,
    ): Collection {
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
            'main_table.block_id = binding_tbl.%s AND resource_type = "%s" AND binding_tbl.store_id = %s',
            ResourceBindingInterface::ORIGINAL_RESOURCE_ID,
            ResourceBindingInterface::CMS_BLOCK_RESOURCE_TYPE,
            Store::DEFAULT_STORE_ID
        );
    }
}
