<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Processor;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\ProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class Pagination implements ProcessorInterface
{
    /**
     * Apply Search Criteria Pagination to collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return void
     */
    public function process(SearchCriteriaInterface $searchCriteria, Collection $collection): void
    {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }
}
