<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\Saas;

use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;
use Aheadworks\Langshop\Model\Saas\Url\Param\Builder as ParamsBuilder;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Serialize\SerializerInterface;

class UrlBuilder
{
    /**
     * @var CurlFactory
     */
    private CurlFactory $curlFactory;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $jsonSerializer;

    /**
     * @var ParamsBuilder
     */
    private ParamsBuilder $paramsBuilder;

    /**
     * @var SaasConfig
     */
    private SaasConfig $config;

    /**
     * @param CurlFactory $curlFactory
     * @param SerializerInterface $jsonSerializer
     * @param SaasConfig $config
     * @param ParamsBuilder $paramsBuilder
     */
    public function __construct(
        CurlFactory $curlFactory,
        SerializerInterface $jsonSerializer,
        SaasConfig $config,
        ParamsBuilder $paramsBuilder
    ) {
        $this->curlFactory = $curlFactory;
        $this->jsonSerializer = $jsonSerializer;
        $this->config = $config;
        $this->paramsBuilder = $paramsBuilder;
    }

    /**
     * Get wizard url
     *
     * @return string
     * @throws IntegrationException
     */
    public function getWizardUrl(): string
    {
        $params = $this->paramsBuilder->buildForMagentoInstallRequest();
        $params = $this->jsonSerializer->serialize($params);
        $curl = $this->curlFactory->create();
        $headers = [
            "Content-Type" => "application/json",
            "Content-Length" => strlen($params)
        ];
        $curl->setHeaders($headers);
        $domain = $this->config->getDomain();
        $curl->post(
            "https://$domain/magento/install",
            $params
        );
        return $this->jsonSerializer->unserialize($curl->getBody());
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
