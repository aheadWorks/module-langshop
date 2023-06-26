<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale\Scope\Record;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface as LocaleScopeRecordInterface;

class Checker
{
    /**
     * Check if the given locale scope record requires applying additional filter
     * for the translatable resource collection
     *
     * @param LocaleScopeRecordInterface $localeScopeRecord
     * @return bool
     */
    public function doesRecordRequireLocaleFilter(LocaleScopeRecordInterface $localeScopeRecord): bool
    {
        return !($localeScopeRecord->getIsPrimary());
    }

    /**
     * Check if the given list of locales contains at least 1 locale to apply
     * filter for the translatable resource collection
     *
     * @param LocaleScopeRecordInterface[] $localeScopeRecordList
     * @return bool
     */
    public function doesListOfRecordsRequireLocaleFilter(array $localeScopeRecordList): bool
    {
        foreach ($localeScopeRecordList as $localeScopeRecord) {
            if ($this->doesRecordRequireLocaleFilter($localeScopeRecord)) {
                return true;
            }
        }
        return false;
    }
}
