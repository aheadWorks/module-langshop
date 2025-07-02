<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\Entity\Binding;

use Magento\Cms\Model\ResourceModel\AbstractCollection;
use Magento\Store\Model\Store;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding as BindingResource;

class TranslationFilter
{
    /**
     * @param BindingResource $bindingResource
     */
    public function __construct(
        private readonly BindingResource $bindingResource
    ) {
    }

    /**
     * Apply translation filter
     *
     * @param AbstractCollection $collection
     * @param string $resourceType
     * @param string $entityFieldName
     * @return void
     */
    public function apply(
        AbstractCollection $collection,
        string $resourceType,
        string $entityFieldName
    ): void {
        $collection->getSelect()
            ->joinInner(
                ['binding_tbl' => $this->bindingResource->getTable(BindingResource::MAIN_TABLE_NAME)],
                $this->getBindingTableJoinCondition($resourceType, $entityFieldName),
                []
            );
    }

    /**
     * Get binding table join condition
     *
     * @param string $resourceType
     * @param string $entityFieldName
     * @return string
     */
    private function getBindingTableJoinCondition(string $resourceType, string $entityFieldName): string
    {
        return sprintf(
            'main_table.%s = binding_tbl.%s AND resource_type = "%s" AND binding_tbl.store_id = %s',
            $entityFieldName,
            ResourceBindingInterface::ORIGINAL_RESOURCE_ID,
            $resourceType,
            Store::DEFAULT_STORE_ID
        );
    }
}
