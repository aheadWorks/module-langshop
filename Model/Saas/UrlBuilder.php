<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas;

use Aheadworks\Langshop\Model\Saas\Request\Install as InstallRequest;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\Serialize\SerializerInterface;

class UrlBuilder
{
    /**
     * @var CurlSender
     */
    private CurlSender $curlSender;

    /**
     * @var InstallRequest
     */
    private InstallRequest $installRequest;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param CurlSender $curlSender
     * @param InstallRequest $installRequest
     * @param SerializerInterface $serializer
     */
    public function __construct(
        CurlSender $curlSender,
        InstallRequest $installRequest,
        SerializerInterface $serializer
    ) {
        $this->curlSender = $curlSender;
        $this->installRequest = $installRequest;
        $this->serializer = $serializer;
    }

    /**
     * Get wizard url
     *
     * @return string
     * @throws IntegrationException
     */
    public function getWizardUrl(): string
    {
        $curl = $this->curlSender->post(
            $this->installRequest->getUrl(),
            $this->installRequest->getParams()
        );

        $body = $this->serializer->unserialize(
            $curl->getBody()
        );

        return $body['url'] ?? '';
    }

    /**
     * Get application url
     *
     * @todo: when Saas will be work set actual url
     * @return string
     */
    public function getApplicationUrl(): string
    {
        return 'https://langshop.io/';
    }
}
