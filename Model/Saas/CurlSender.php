<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas;

use Exception;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class CurlSender
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
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param CurlFactory $curlFactory
     * @param LoggerInterface $logger
     * @param SerializerInterface $serializer
     */
    public function __construct(
        CurlFactory $curlFactory,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $this->curlFactory = $curlFactory;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * POST request by given URL and parameters
     *
     * @param string $url
     * @param array $params
     * @return Curl
     */
    public function post(string $url, array $params): Curl
    {
        $curl = $this->curlFactory->create();

        try {
            $curl->post($url, $this->serializer->serialize($params));
        } catch (Exception $exception) {
            $this->logger->error($exception);
        }

        return $curl;
    }
}
