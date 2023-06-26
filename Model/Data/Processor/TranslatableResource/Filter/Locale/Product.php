<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource\Filter\Locale;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface as LocaleScopeRecordInterface;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\Entity\Field\Filter\Builder as FilterBuilder;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Checker as LocaleScopeRecordChecker;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type as LocaleScopeTypeSourceModel;
use Magento\Framework\Api\Filter;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

class Product implements ProcessorInterface
{
    private const FILTER_FIELD_NAME = 'website_id';
    private const FILTER_TYPE = 'product_website_id';

    /**
     * @param FilterBuilder $filterBuilder
     * @param StoreManagerInterface $storeManager
     * @param LocaleScopeRecordChecker $localeScopeRecordChecker
     * @param FilterListModifier $filterListModifier
     */
    public function __construct(
        private FilterBuilder $filterBuilder,
        private StoreManagerInterface $storeManager,
        private LocaleScopeRecordChecker $localeScopeRecordChecker,
        private FilterListModifier $filterListModifier
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

        if ($this->localeScopeRecordChecker->doesListOfRecordsRequireLocaleFilter($localeScopeRecordList)) {
            $websiteIdList = $this->getWebsiteIdList($localeScopeRecordList);

            $websiteIdFilter = $this->filterBuilder->create(
                self::FILTER_FIELD_NAME,
                $websiteIdList,
                self::FILTER_TYPE
            );

            $data['filter'] = $this->filterListModifier->addOrReplaceFilter($data['filter'], $websiteIdFilter);
        }

        return $data;
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
            if ($this->localeScopeRecordChecker->doesRecordRequireLocaleFilter($localeScopeRecord)) {
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
}
