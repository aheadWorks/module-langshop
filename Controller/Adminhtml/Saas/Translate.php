<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Controller\Adminhtml\Saas;

use Aheadworks\Langshop\Api\Data\StatusInterface;
use Aheadworks\Langshop\Api\Data\StatusInterfaceFactory;
use Aheadworks\Langshop\Api\StatusManagementInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as ScopeRecordRepository;
use Aheadworks\Langshop\Model\Saas\CurlSender;
use Aheadworks\Langshop\Model\Saas\Request\Translate as TranslateRequest;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Translate extends Action implements HttpPostActionInterface
{
    /**
     * @var CurlSender
     */
    private CurlSender $curlSender;

    /**
     * @var TranslateRequest
     */
    private TranslateRequest $translateRequest;

    /**
     * @var StatusManagementInterface
     */
    private StatusManagementInterface $statusManager;

    /**
     * @var StatusInterfaceFactory
     */
    private StatusInterfaceFactory $statusFactory;

    /**
     * @var ScopeRecordRepository
     */
    private ScopeRecordRepository $scopeRecordRepository;

    /**
     * @param Context $context
     * @param CurlSender $curlSender
     * @param TranslateRequest $translateRequest
     * @param StatusManagementInterface $statusManager
     * @param StatusInterfaceFactory $statusFactory
     * @param ScopeRecordRepository $scopeRecordRepository
     */
    public function __construct(
        Context $context,
        CurlSender $curlSender,
        TranslateRequest $translateRequest,
        StatusManagementInterface $statusManager,
        StatusInterfaceFactory $statusFactory,
        ScopeRecordRepository $scopeRecordRepository
    ) {
        parent::__construct($context);

        $this->curlSender = $curlSender;
        $this->translateRequest = $translateRequest;
        $this->statusManager = $statusManager;
        $this->statusFactory = $statusFactory;
        $this->scopeRecordRepository = $scopeRecordRepository;
    }

    /**
     * Sends request to Langshop to translate particular resource
     *
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $resourceType = (string) $this->getRequest()->getParam('resource_type');
        $resourceId = (int) $this->getRequest()->getParam('resource_id');

        $this->curlSender->post(
            $this->translateRequest->getUrl(),
            $this->translateRequest->getParams(
                $resourceType,
                $resourceId
            )
        );

        foreach ($this->scopeRecordRepository->getList() as $scopeRecord) {
            /** @var StatusInterface $status */
            $status = $this->statusFactory->create()
                ->setResourceId($resourceId)
                ->setResourceType($resourceType)
                ->setStatus(StatusInterface::STATUS_PROCESSING)
                ->setStoreId((int)$scopeRecord->getScopeId());
            $this->statusManager->save($status);
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON);
    }
}
