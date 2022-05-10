<?php
declare(strict_types = 1);
namespace Aheadworks\Langshop\ViewModel\Page;

use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;
use Aheadworks\Langshop\Model\Saas\UrlBuilder;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Iframe implements ArgumentInterface
{
    /**
     * @var SaasConfig
     */
    private SaasConfig $config;

    /**
     * @var UrlBuilder
     */
    private UrlBuilder $urlBuilder;

    /**
     * @param SaasConfig $config
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(
        SaasConfig $config,
        UrlBuilder $urlBuilder
    ) {
        $this->config = $config;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Get source url
     *
     * @return string
     * @throws IntegrationException
     */
    public function getSourceUrl(): string
    {
        $key = $this->config->getPublicKey();

        return $key
            ? $this->urlBuilder->getApplicationUrl()
            : $this->urlBuilder->getWizardUrl();
    }
}
