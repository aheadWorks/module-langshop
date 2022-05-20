<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Controller\Adminhtml\Saas;

use Aheadworks\Langshop\Model\Saas\Request\Translate as TranslateRequest;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class Translate extends Action implements HttpPostActionInterface
{
    /**
     * @var CurlFactory
     */
    private CurlFactory $curlFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var TranslateRequest
     */
    private TranslateRequest $translateRequest;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Context $context
     * @param CurlFactory $curlFactory
     * @param LoggerInterface $logger
     * @param TranslateRequest $translateRequest
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Context $context,
        CurlFactory $curlFactory,
        LoggerInterface $logger,
        TranslateRequest $translateRequest,
        SerializerInterface $serializer
    ) {
        parent::__construct($context);

        $this->curlFactory = $curlFactory;
        $this->logger = $logger;
        $this->translateRequest = $translateRequest;
        $this->serializer = $serializer;
    }

    /**
     * Sends request to Langshop to translate particular resource
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $result = [
            'error' => false,
            'message' => __('Translation started. Please wait.')
        ];

        $curl = $this->curlFactory->create();

        try {
            $curl->post(
                $this->translateRequest->getUrl(),
                $this->serializer->serialize($this->getRequestParams())
            );

            if ($curl->getStatus() !== 200) {
                throw new Exception();
            }
        } catch (Exception $exception) {
            $this->logger->error($exception);

            $result = [
                'error' => true,
                'message' => __('A technical problem with the server created an error.')
            ];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
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
