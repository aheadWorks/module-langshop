<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource\Filter\Locale;

use Magento\Framework\Api\Filter;

class FilterListModifier
{
    /**
     * Add the filter to the list of already prepared filters
     * In case, if the filter for the same field name
     * has been added earlier, replace the old filter with the new one
     *
     * @param Filter[] $filterList
     * @param Filter $newFilter
     * @return array
     */
    public function addOrReplaceFilter(array $filterList, Filter $newFilter): array
    {
        $indexOfExistingWebsiteIdFilter = null;
        foreach ($filterList as $index => $filter) {
            if ($filter->getField() === $newFilter->getField()) {
                $indexOfExistingWebsiteIdFilter = $index;
                break;
            }
        }

        if ($indexOfExistingWebsiteIdFilter !== null) {
            unset($filterList[$indexOfExistingWebsiteIdFilter]);
        }

        $filterList[] = $newFilter;
        return $filterList;
    }
}
