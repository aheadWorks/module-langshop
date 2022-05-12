<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas;

use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;
use Aheadworks\Langshop\Model\Saas\Url\Param\Builder as ParamsBuilder;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\HTTP\Client\CurlFactory;

class UrlBuilder
{
    /**
     * @var CurlFactory
     */
    private CurlFactory $curlFactory;

    /**
     * @var SaasConfig
     */
    private SaasConfig $saasConfig;

    /**
     * @var ParamsBuilder
     */
    private ParamsBuilder $paramsBuilder;

    /**
     * @param SaasConfig $saasConfig
     * @param CurlFactory $curlFactory
     * @param ParamsBuilder $paramsBuilder
     */
    public function __construct(
        SaasConfig $saasConfig,
        CurlFactory $curlFactory,
        ParamsBuilder $paramsBuilder
    ) {
        $this->saasConfig = $saasConfig;
        $this->curlFactory = $curlFactory;
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
        $curl = $this->curlFactory->create();
        $domain = $this->saasConfig->getDomain();

        $curl->post(
            "https://$domain/magento/install",
            $this->paramsBuilder->buildForMagentoInstallRequest()
        );

        return $curl->getBody();
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
