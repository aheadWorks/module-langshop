<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\Collection\Processor;

use Aheadworks\Langshop\Model\ResourceModel\Collection\ProcessorInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection;
use Magento\Framework\Exception\LocalizedException;

class Filter implements ProcessorInterface
{
    /**
     * Apply Search Criteria Filters to collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return void
     * @throws LocalizedException
     */
    public function process(SearchCriteriaInterface $searchCriteria, Collection $collection): void
    {
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
    }

    /**
     * Add FilterGroup to the collection
     *
     * @param FilterGroup $filterGroup
     * @param Collection $collection
     * @return void
     * @throws LocalizedException
     */
    private function addFilterGroupToCollection(
        FilterGroup $filterGroup,
        Collection $collection
    ): void {
        $fields = [];
        $conditions = [];

        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ?? 'eq';
            $fields[] = $filter->getField();

            $conditions[] = [$condition => $filter->getValue()];
        }

        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }
}
