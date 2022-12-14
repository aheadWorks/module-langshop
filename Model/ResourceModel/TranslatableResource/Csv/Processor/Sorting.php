<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Processor;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\ProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;

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
            $collection->addOrder($sortOrder->getField(), $sortOrder->getDirection());
        }
    }
}
