<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\Collection;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection;

interface ProcessorInterface
{
    /**
     * Apply Search Criteria to Collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @throws \InvalidArgumentException
     * @return void
     */
    public function process(SearchCriteriaInterface $searchCriteria, Collection $collection): void;
}
