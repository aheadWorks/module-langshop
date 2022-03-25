<?php
namespace Aheadworks\Langshop\Model\Config\Backend\Locale\Scope\ListToTranslate;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface as LocaleScopeRecordInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRecordRepository;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type as LocaleScopeTypeSourceModel;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Persister
{
    /**
     * @var LocaleScopeRecordRepository
     */
    private $localeScopeRecordRepository;

    /**
     * @var Converter
     */
    private $converter;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LocaleScopeRecordRepository $localeScopeRecordRepository
     * @param Converter $converter
     * @param SearchCriteriaBuilder $searchCriteriaBuilder,
     * @param LoggerInterface $logger
     */
    public function __construct(
        LocaleScopeRecordRepository $localeScopeRecordRepository,
        Converter $converter,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger
    ) {
        $this->localeScopeRecordRepository = $localeScopeRecordRepository;
        $this->converter = $converter;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
    }

    /**
     * Retrieve prepared and serialized list of locale scope UIDs to translate
     *
     * @return string
     */
    public function getSerializedScopeUidList()
    {
        $localeScopeRecordListOfNonDefaultType = $this->getLocaleScopeRecordListOfNonDefaultType();

        return $this->converter->fromLocaleScopeRecordListToSerializedScopeUidList(
            $localeScopeRecordListOfNonDefaultType
        );
    }

    /**
     * Save serialized list of locale scope UIDs to translate
     *
     * @param string $serializedScopeUidList
     * @return $this
     */
    public function saveSerializedScopeUidList($serializedScopeUidList)
    {
        //TODO: consider performing delta-saving instead of complete removal older records
        $this->removeLocaleScopeRecordList(
            $this->getLocaleScopeRecordListOfNonDefaultType()
        );

        $localeScopeRecordListToSave = $this->converter->fromSerializedScopeUidListToLocaleScopeRecordList(
            $serializedScopeUidList
        );

        $this->saveLocaleScopeRecordList($localeScopeRecordListToSave);

        return $this;
    }

    /**
     * Retrieve the list of records, which doesn't belong to the default scope type
     *
     * @return LocaleScopeRecordInterface[]
     */
    private function getLocaleScopeRecordListOfNonDefaultType()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(
                LocaleScopeRecordInterface::SCOPE_TYPE,
                LocaleScopeTypeSourceModel::DEFAULT,
                'neq'
            )->create()
        ;
        return $this->localeScopeRecordRepository
            ->getList($searchCriteria)
            ->getItems()
        ;
    }

    /**
     * Remove all the locale scope records list of the provided list
     *
     * @param LocaleScopeRecordInterface[] $localeScopeRecordList
     * @return int
     */
    private function removeLocaleScopeRecordList($localeScopeRecordList)
    {
        $deletedRecordsCount = 0;
        foreach ($localeScopeRecordList as $localeScopeRecord) {
            try {
                $this->localeScopeRecordRepository->delete($localeScopeRecord);
                $deletedRecordsCount++;
            } catch (LocalizedException $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
        return $deletedRecordsCount;
    }

    /**
     * Save all the locale scope records list of the provided list
     *
     * @param LocaleScopeRecordInterface[] $localeScopeRecordList
     * @return int
     */
    private function saveLocaleScopeRecordList($localeScopeRecordList)
    {
        $savedRecordsCount = 0;
        foreach ($localeScopeRecordList as $localeScopeRecord) {
            try {
                $this->localeScopeRecordRepository->save($localeScopeRecord);
                $savedRecordsCount++;
            } catch (LocalizedException $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
        return $savedRecordsCount;
    }
}
