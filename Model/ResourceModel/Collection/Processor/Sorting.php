<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\Collection\Processor;

use Aheadworks\Langshop\Model\ResourceModel\Collection\ProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Data\Collection;

class Sorting implements ProcessorInterface
{
    /**
     * Apply Search Criteria Sorting Orders to collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return void
     */
    public function process(SearchCriteriaInterface $searchCriteria, Collection $collection): void
    {
        if ($searchCriteria->getSortOrders()) {
            $this->applyOrders($searchCriteria->getSortOrders(), $collection);
        }
    }

    /**
     * Apply sort orders to collection
     *
     * @param SortOrder[] $sortOrders
     * @param Collection $collection
     * @return void
     */
    private function applyOrders(array $sortOrders, Collection $collection): void
    {
        foreach ($sortOrders as $sortOrder) {
            $field = $sortOrder->getField();
            $order = $sortOrder->getDirection() ?? SortOrder::SORT_ASC;
            $collection->addOrder($field, $order);
        }
    }
}
