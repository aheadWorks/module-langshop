<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Field\Filter\Product;

use Aheadworks\Langshop\Model\Entity\Field\Filter\PreparerInterface;

class WebsiteId implements PreparerInterface
{
    /**
     * Get prepared condition type
     * In fact, has no effect on the final result, because it's nou used in the
     * corresponding filter applier
     * Reference: \Magento\Catalog\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor\ProductWebsiteFilter
     *
     * @param string[] $value
     * @return string
     */
    public function getPreparedConditionType(array $value): string
    {
        return 'in';
    }

    /**
     * Convert the list of website ids to the comma-separated string format,
     * required by the corresponding filter applier
     * Reference: \Magento\Catalog\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor\ProductWebsiteFilter
     *
     * @param string[] $value
     * @return string
     */
    public function getPreparedValue(array $value)
    {
        return implode(',', $value);
    }
}
