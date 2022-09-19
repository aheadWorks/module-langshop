<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas;

use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;
use Aheadworks\Langshop\Model\Saas\Request\Install as InstallRequest;
use Magento\Backend\Model\Url;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\Serialize\SerializerInterface;

class UrlBuilder
{
    /**
     * @var SaasConfig
     */
    private SaasConfig $saasConfig;

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
     * @var Url
     */
    private Url $url;

    /**
     * @param SaasConfig $saasConfig
     * @param CurlSender $curlSender
     * @param InstallRequest $installRequest
     * @param SerializerInterface $serializer
     * @param Url $url
     */
    public function __construct(
        SaasConfig $saasConfig,
        CurlSender $curlSender,
        InstallRequest $installRequest,
        SerializerInterface $serializer,
        Url $url
    ) {
        $this->saasConfig = $saasConfig;
        $this->curlSender = $curlSender;
        $this->installRequest = $installRequest;
        $this->serializer = $serializer;
        $this->url = $url;
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
     * @return string
     */
    public function getApplicationUrl(): string
    {
        return sprintf(
            '%slogin?token=%s',
            $this->saasConfig->getDomain(),
            $this->saasConfig->getPublicKey()
        );
    }

    /**
     * Get application url
     *
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->url->getUrl('langshop/saas/redirect');
    }
}
