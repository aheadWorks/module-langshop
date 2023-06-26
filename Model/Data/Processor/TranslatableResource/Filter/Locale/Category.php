<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource\Filter\Locale;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface as LocaleScopeRecordInterface;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\Entity\Field\Filter\Builder as FilterBuilder;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Checker as LocaleScopeRecordChecker;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type as LocaleScopeTypeSourceModel;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

class Category implements ProcessorInterface
{
    private const FILTER_FIELD_NAME = 'path';
    private const FILTER_TYPE = 'select';

    /**
     * @param FilterBuilder $filterBuilder
     * @param StoreManagerInterface $storeManager
     * @param CategoryRepositoryInterface $categoryRepository
     * @param LocaleScopeRecordChecker $localeScopeRecordChecker
     */
    public function __construct(
        private FilterBuilder $filterBuilder,
        private StoreManagerInterface $storeManager,
        private CategoryRepositoryInterface $categoryRepository,
        private LocaleScopeRecordChecker $localeScopeRecordChecker
    ) {
    }

    /**
     * Prepares filter for search criteria to limit the list of categories
     * based on the list of locales specified and corresponding root categories,
     * chosen for connected store groups
     *
     * @param array $data
     * @return array
     * @throws LocalizedException
     */
    public function process(array $data): array
    {
        /** @var LocaleScopeRecordInterface[] $localeScopeRecordList */
        $localeScopeRecordList = $data['locale'] ?? [];

        if ($this->localeScopeRecordChecker->doesListOfRecordsRequireLocaleFilter($localeScopeRecordList)) {
            $storeGroupIdList = $this->getStoreGroupIdList($localeScopeRecordList);
            $rootCategoryIdList = $this->getRootCategoryIdList($storeGroupIdList);
            $rootCategoryPathList = $this->getCategoryPathList($rootCategoryIdList);

            $pathFilter = $this->filterBuilder->create(
                self::FILTER_FIELD_NAME,
                $rootCategoryPathList,
                self::FILTER_TYPE
            );

            $data['filter'] = $this->addPathFilterToList($data['filter'], $pathFilter);
        }

        return $data;
    }

    /**
     * Retrieve the list of store group id, where the given list of locales is used
     *
     * @param LocaleScopeRecordInterface[] $localeScopeRecordList
     * @return array
     * @throws LocalizedException
     */
    private function getStoreGroupIdList(array $localeScopeRecordList): array
    {
        $storeGroupIdList = [];
        foreach ($localeScopeRecordList as $localeScopeRecord) {
            if ($this->localeScopeRecordChecker->doesRecordRequireLocaleFilter($localeScopeRecord)) {
                // To simplify the logic, assume, that all records have scope type either
                // DEFAULT or STORE. The DEFAULT should be already filtered out with isNeedToApplyFilterForLocale()
                if ($localeScopeRecord->getScopeType() === LocaleScopeTypeSourceModel::STORE) {
                    $storeGroupIdList[] = $this->storeManager
                        ->getStore($localeScopeRecord->getScopeId())
                        ->getStoreGroupId()
                    ;
                }
            }
        }

        return array_unique($storeGroupIdList);
    }

    /**
     * Extract the list of root category ids for the given list of store group ids
     *
     * @param array $storeGroupIdList
     * @return int[]
     */
    private function getRootCategoryIdList(array $storeGroupIdList): array
    {
        $rootCategoryIdList = [];

        foreach ($storeGroupIdList as $storeGroupId) {
            $storeGroup = $this->storeManager->getGroup($storeGroupId);
            $rootCategoryIdList[] = (int)$storeGroup->getRootCategoryId();
        }

        return array_unique($rootCategoryIdList);
    }

    /**
     * For the given list of category id, extract the list of category full hierarchical path
     *
     * @param int[] $categoryIdList
     * @return array
     * @throws LocalizedException
     */
    private function getCategoryPathList(array $categoryIdList): array
    {
        $categoryPathList = [];

        foreach ($categoryIdList as $categoryId) {
            $category = $this->categoryRepository->get($categoryId);
            $categoryPathList[] = $category->getPath();
        }

        return $categoryPathList;
    }

    /**
     * Add path filter to the list of already prepared filters
     * In case, if path filter has been added earlier, replace the old filter
     * with the new one
     *
     * @param Filter[] $filterList
     * @param Filter $pathFilter
     * @return array
     */
    private function addPathFilterToList(array $filterList, Filter $pathFilter): array
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

        $filterList[] = $pathFilter;
        return $filterList;
    }
}
