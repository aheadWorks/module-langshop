<?php
namespace Aheadworks\Langshop\Model\Locale\ScopeRecord;

use Magento\Framework\Api\SearchResultsInterface
    as FrameworkSearchResultsInterface;
use Aheadworks\Langshop\Model\Locale\ScopeRecordInterface
    as LocaleScopeRecordInterface;

interface SearchResultsInterface extends FrameworkSearchResultsInterface
{
    /**
     * Get the list of locale scope records
     *
     * @return LocaleScopeRecordInterface[]
     */
    public function getItems();

    /**
     * Set the list of locale scope records
     *
     * @param LocaleScopeRecordInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
