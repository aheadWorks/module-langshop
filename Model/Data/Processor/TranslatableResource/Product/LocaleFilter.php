<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource\Product;

use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Langshop\Model\Entity\Field\Filter\Builder as FilterBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface as LocaleScopeRecordInterface;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type as LocaleScopeTypeSourceModel;

class LocaleFilter implements ProcessorInterface
{
    private const FILTER_FIELD_NAME = 'website_id';
    private const FILTER_TYPE = 'website_id';

    /**
     * @param FilterBuilder $filterBuilder
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private FilterBuilder $filterBuilder,
        private StoreManagerInterface $storeManager
    ) {
    }

    /**
     * Prepares filter for search criteria to limit the list of products
     * based on the list of locales specified and corresponding websites,
     * where those locales are used
     *
     * @param array $data
     * @return array
     * @throws LocalizedException
     */
    public function process(array $data): array
    {
        /** @var LocaleScopeRecordInterface[] $localeScopeRecordList */
        $localeScopeRecordList = $data['locale'] ?? [];

        if ($this->isNeedToApplyLocaleFilter($localeScopeRecordList)) {
            $websiteIdList = $this->getWebsiteIdList($localeScopeRecordList);

            $websiteIdFilter = $this->filterBuilder->create(
                self::FILTER_FIELD_NAME,
                $websiteIdList,
                self::FILTER_TYPE
            );

            $data['filter'] = $this->addWebsiteIdFilterToList($data['filter'], $websiteIdFilter);
        }

        return $data;
    }

    /**
     * Check if the given locale scope record requires applying additional filter
     * for the product collection
     *
     * @param LocaleScopeRecordInterface $localeScopeRecord
     * @return bool
     */
    private function isNeedToApplyFilterForLocale(LocaleScopeRecordInterface $localeScopeRecord): bool
    {
        return !($localeScopeRecord->getIsPrimary());
    }

    /**
     * Check if the given list of locales contains at least 1 locale to apply
     * filter for the product collection
     *
     * @param LocaleScopeRecordInterface[] $localeScopeRecordList
     * @return bool
     */
    private function isNeedToApplyLocaleFilter(array $localeScopeRecordList): bool
    {
        foreach ($localeScopeRecordList as $localeScopeRecord) {
            if ($this->isNeedToApplyFilterForLocale($localeScopeRecord)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieve the list of website id, where the given list of locales is used
     *
     * @param LocaleScopeRecordInterface[] $localeScopeRecordList
     * @return array
     * @throws LocalizedException
     */
    private function getWebsiteIdList(array $localeScopeRecordList): array
    {
        $websiteIdList = [];
        foreach ($localeScopeRecordList as $localeScopeRecord) {
            if ($this->isNeedToApplyFilterForLocale($localeScopeRecord)) {
                if ($localeScopeRecord->getScopeType() === LocaleScopeTypeSourceModel::STORE) {
                    $websiteIdList[] = $this->storeManager->getStore($localeScopeRecord->getScopeId())->getWebsiteId();
                }
                if ($localeScopeRecord->getScopeType() === LocaleScopeTypeSourceModel::WEBSITE) {
                    $websiteIdList[] = $localeScopeRecord->getScopeId();
                }
            }
        }

        return array_unique($websiteIdList);
    }

    /**
     * Add website id filter to the list of already prepared filters
     * In case, if website id filter has been added earlier, replace the old filter
     * with the new one
     *
     * @param Filter[] $filterList
     * @param Filter $websiteIdFilter
     * @return array
     */
    private function addWebsiteIdFilterToList(array $filterList, Filter $websiteIdFilter): array
    {
        $indexOfExistingWebsiteIdFilter = null;
        foreach ($filterList as $index => $filter) {
            if ($filter->getField() === self::FILTER_FIELD_NAME) {
                $indexOfExistingWebsiteIdFilter = $index;
                break;
            }
        }

        if ($indexOfExistingWebsiteIdFilter !== null) {
            unset($filterList[$indexOfExistingWebsiteIdFilter]);
        }

        $filterList[] = $websiteIdFilter;
        return $filterList;
    }
}
