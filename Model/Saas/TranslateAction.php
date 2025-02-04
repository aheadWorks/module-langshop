<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Webapi\Exception;
use Aheadworks\Langshop\Api\Data\StatusInterface;
use Aheadworks\Langshop\Api\Data\StatusInterfaceFactory;
use Aheadworks\Langshop\Api\StatusManagementInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as ScopeRecordRepository;
use Aheadworks\Langshop\Model\Saas\Request\Translate as TranslateRequest;

class TranslateAction
{
    /**
     * @param \Aheadworks\Langshop\Model\Saas\CurlSender $curlSender
     * @param TranslateRequest $translateRequest
     * @param StatusManagementInterface $statusManager
     * @param StatusInterfaceFactory $statusFactory
     * @param ScopeRecordRepository $scopeRecordRepository
     */
    public function __construct(
        private readonly CurlSender $curlSender,
        private readonly TranslateRequest $translateRequest,
        private readonly StatusManagementInterface $statusManager,
        private readonly StatusInterfaceFactory $statusFactory,
        private readonly ScopeRecordRepository $scopeRecordRepository
    ) {
    }

    /**
     * Make request
     *
     * @param string $resourceType
     * @param int $resourceId
     * @return Curl
     * @throws LocalizedException
     * @throws Exception
     */
    public function makeRequest(string $resourceType, int $resourceId): Curl
    {
        return $this->curlSender->post(
            $this->translateRequest->getUrl(),
            $this->translateRequest->getParams(
                $resourceType,
                $resourceId
            )
        );
    }

    /**
     * Set processing status
     *
     * @param string $resourceType
     * @param int $resourceId
     * @return void
     * @throws LocalizedException
     * @throws Exception
     */
    public function setProcessingStatus(string $resourceType, int $resourceId): void
    {
        foreach ($this->scopeRecordRepository->getList() as $scopeRecord) {
            /** @var StatusInterface $status */
            $status = $this->statusFactory->create()
                ->setResourceId($resourceId)
                ->setResourceType($resourceType)
                ->setStatus(StatusInterface::STATUS_PROCESSING)
                ->setStoreId($scopeRecord->getScopeId());
            $this->statusManager->save($status);
        }
    }
}
