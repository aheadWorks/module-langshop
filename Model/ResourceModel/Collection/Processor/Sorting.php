<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\Collection\Processor;

use Aheadworks\Langshop\Model\ResourceModel\Collection\ProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
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
        //todo https://aheadworks.atlassian.net/browse/LSM2-171
    }
}
