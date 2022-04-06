<?php
namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as ScopeRecordRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;

class Locale implements ProcessorInterface
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ScopeRecordRepository
     */
    private $scopeRecordRepository;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ScopeRecordRepository $scopeRecordRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ScopeRecordRepository $scopeRecordRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->scopeRecordRepository = $scopeRecordRepository;
    }

    /**
     * Get store ids by locale code
     *
     * @param array $data
     * @return array
     * @throws NoSuchEntityException
     */
    public function process($data)
    {
        $locale = $data['locale'] ?? $this->scopeRecordRepository->getDefault()->getLocaleCode();
        $storeIds = [];
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(
                RecordInterface::LOCALE_CODE,
                is_array($locale) ? $locale : [$locale],
                'in'
            )->addFilter(
                RecordInterface::SCOPE_TYPE,
                'store'
            )->create();

        $scopeRecords = $this->scopeRecordRepository->getList($searchCriteria);
        foreach ($scopeRecords->getItems() as $scopeRecord) {
            $storeIds[$scopeRecord->getScopeId()] = $scopeRecord->getLocaleCode();
        }
        $data['store_ids'] = $storeIds;

        return $data;
    }
}
