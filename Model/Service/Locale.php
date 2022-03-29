<?php
namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\LocaleManagementInterface;
use Aheadworks\Langshop\Model\Locale\LoadHandler;
use Aheadworks\Langshop\Model\Locale\SaveHandler;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as ScopeRecordRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Webapi\Exception as WebapiException;

class Locale implements LocaleManagementInterface
{
    /**
     * @var ScopeRecordRepository
     */
    private $scopeRecordRepository;

    /**
     * @var SaveHandler
     */
    private $saveHandler;

    /**
     * @var LoadHandler
     */
    private $loadHandler;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param ScopeRecordRepository $scopeRecordRepository
     * @param SaveHandler $saveHandler
     * @param LoadHandler $loadHandler
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ScopeRecordRepository $scopeRecordRepository,
        SaveHandler $saveHandler,
        LoadHandler $loadHandler,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->scopeRecordRepository = $scopeRecordRepository;
        $this->loadHandler = $loadHandler;
        $this->saveHandler = $saveHandler;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritDoc
     * @throws WebapiException
     * @codingStandardsIgnoreStart
     */
    public function add()
    {
        throw new WebapiException(__('This method is not implemented yet.'), 501, 501);
    }

    /**
     * @inheritDoc
     * @throws WebapiException
     */
    public function update($locale)
    {
        throw new WebapiException(__('This method is not implemented yet.'), 501, 501);
    }

    /**
     * @inheritDoc
     * @throws WebapiException
     */
    public function delete($locale)
    {
        throw new WebapiException(__('This method is not implemented yet.'), 501, 501);
    }
    /* @codingStandardsIgnoreEnd  */

    /**
     * @inheritDoc
     */
    public function getList()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $scopeRecords = $this->scopeRecordRepository
            ->getList($searchCriteria)
            ->getItems();
        $existingLocales = [];
        $locales = [];

        foreach ($scopeRecords as $scopeRecord) {
            $localeCode = $scopeRecord->getLocaleCode();
            if (!isset($existingLocales[$localeCode])) {
                $locales[] = $this->loadHandler->load($scopeRecord);
                $existingLocales[] = $localeCode;
            }
        }

        return $locales;
    }
}
