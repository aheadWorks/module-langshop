<?php
namespace Aheadworks\Langshop\Model\Locale\Scope\Record;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface as LocaleScopeRecordInterface;
use Magento\Framework\Api\SearchResultsInterface as FrameworkSearchResultsInterface;

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
