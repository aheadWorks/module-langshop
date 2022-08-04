<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\LocaleManagementInterface;
use Aheadworks\Langshop\Model\Locale\LoadHandler;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as ScopeRecordRepository;
use Magento\Framework\Webapi\Exception as WebapiException;

class Locale implements LocaleManagementInterface
{
    /**
     * @var ScopeRecordRepository
     */
    private ScopeRecordRepository $scopeRecordRepository;

    /**
     * @var LoadHandler
     */
    private LoadHandler $loadHandler;

    /**
     * @param ScopeRecordRepository $scopeRecordRepository
     * @param LoadHandler $loadHandler
     */
    public function __construct(
        ScopeRecordRepository $scopeRecordRepository,
        LoadHandler $loadHandler
    ) {
        $this->scopeRecordRepository = $scopeRecordRepository;
        $this->loadHandler = $loadHandler;
    }

    /**
     * @inheritDoc
     *
     * @throws WebapiException
     */
    public function add()
    {
        throw new WebapiException(__('This method is not implemented yet.'), 501, 501);
    }

    /**
     * @inheritDoc
     *
     * @throws WebapiException
     */
    public function update($locale)
    {
        throw new WebapiException(__('This method is not implemented yet.'), 501, 501);
    }

    /**
     * @inheritDoc
     *
     * @throws WebapiException
     */
    public function delete($locale)
    {
        throw new WebapiException(__('This method is not implemented yet.'), 501, 501);
    }

    /**
     * @inheritDoc
     */
    public function getList(): array
    {
        $scopeRecords = array_merge(
            [$this->scopeRecordRepository->getPrimary()],
            $this->scopeRecordRepository->getList()
        );

        $existingLocales = [];
        $locales = [];

        foreach ($scopeRecords as $scopeRecord) {
            $localeCode = $scopeRecord->getLocaleCode();
            if (!in_array($localeCode, $existingLocales)) {
                $locales[] = $this->loadHandler->load($scopeRecord);
                $existingLocales[] = $localeCode;
            }
        }

        return $locales;
    }
}
