<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository\CollectionProcessor\FilterProcessor\CustomFilter\Category;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;

class Path implements CustomFilterInterface
{
    /**
     * Apply category path filter to th category collection
     *
     * @param Filter $filter
     * @param AbstractDb $collection
     * @return bool Whether the filter was applied
     */
    public function apply(Filter $filter, AbstractDb $collection)
    {
        if ($collection instanceof CategoryCollection) {
            $collection->addPathsFilter($filter->getValue());
            return true;
        }

        return false;
    }
}
