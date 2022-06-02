<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\LocaleManagementInterface;
use Aheadworks\Langshop\Model\Locale\LoadHandler;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as ScopeRecordRepository;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Exception as WebapiException;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param ScopeRecordRepository $scopeRecordRepository
     * @param LoadHandler $loadHandler
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeRecordRepository $scopeRecordRepository,
        LoadHandler $loadHandler,
        LoggerInterface $logger
    ) {
        $this->scopeRecordRepository = $scopeRecordRepository;
        $this->loadHandler = $loadHandler;
        $this->logger = $logger;
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
        try {
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
        } catch (LocalizedException $exception) {
            $this->logger->error($exception->getMessage());
            throw new WebapiException(__($exception->getMessage()), 500, 500);
        }

        return $locales;
    }
}
