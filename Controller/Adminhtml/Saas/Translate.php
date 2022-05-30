<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Controller\Adminhtml\Saas;

use Aheadworks\Langshop\Model\Saas\CurlSender;
use Aheadworks\Langshop\Model\Saas\Request\Translate as TranslateRequest;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json as ResultJson;
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
     * @param Context $context
     * @param CurlSender $curlSender
     * @param TranslateRequest $translateRequest
     */
    public function __construct(
        Context $context,
        CurlSender $curlSender,
        TranslateRequest $translateRequest
    ) {
        parent::__construct($context);

        $this->curlSender = $curlSender;
        $this->translateRequest = $translateRequest;
    }

    /**
     * Sends request to Langshop to translate particular resource
     *
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $result = [
            'error' => false,
            'message' => __('Translation started. Please wait.')
        ];

        $curl = $this->curlSender->post(
            $this->translateRequest->getUrl(),
            $this->getRequestParams()
        );

        if ($curl->getStatus() !== 200) {
            $result = [
                'error' => true,
                'message' => __('A technical problem with the server created an error.')
            ];
        }

        /** @var ResultJson $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        return $resultJson->setData($result);
    }

    /**
     * Retrieves parameters for the translate request
     *
     * @return array
     * @throws LocalizedException
     */
    private function getRequestParams(): array
    {
        $resourceType = (string) $this->getRequest()->getParam('resource_type');
        $resourceId = (int) $this->getRequest()->getParam('resource_id');

        return $this->translateRequest->getParams(
            $resourceType,
            $resourceId
        );
    }
}
