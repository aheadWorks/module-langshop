<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Controller\Adminhtml\Saas;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json as ResultJson;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Aheadworks\Langshop\Model\Saas\CurlResponseHandler;
use Aheadworks\Langshop\Model\Saas\TranslateAction;

class Translate extends Action implements HttpPostActionInterface
{
    /**
     * @param Context $context
     * @param CurlResponseHandler $curlResponseHandler
     * @param TranslateAction $translateAction
     */
    public function __construct(
        Context $context,
        private readonly CurlResponseHandler $curlResponseHandler,
        private readonly TranslateAction $translateAction
    ) {
        parent::__construct($context);
    }

    /**
     * Sends request to Langshop to translate particular resource
     *
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute(): ResultInterface
    {
        $resourceType = (string) $this->getRequest()->getParam('resource_type');
        $resourceId = (int) $this->getRequest()->getParam('resource_id');

        $response = $this->translateAction->makeRequest($resourceType, $resourceId);

        /** @var ResultJson $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        if ($response->getStatus() > 400) {
            return $resultJson->setData(
                $this->curlResponseHandler->prepareResponse($response)
            );
        }

        $this->translateAction->setProcessingStatus($resourceType, $resourceId);
        return $resultJson->setData([]);
    }
}
