<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv;

use Magento\Framework\Api\SearchCriteriaInterface;

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
