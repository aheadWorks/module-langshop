<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas;

use Aheadworks\Langshop\Model\Saas\Request\Install as InstallRequest;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\HTTP\Client\CurlFactory;

class UrlBuilder
{
    /**
     * @var CurlFactory
     */
    private CurlFactory $curlFactory;

    /**
     * @var InstallRequest
     */
    private InstallRequest $installRequest;

    /**
     * @param CurlFactory $curlFactory
     * @param InstallRequest $installRequest
     */
    public function __construct(
        CurlFactory $curlFactory,
        InstallRequest $installRequest
    ) {
        $this->curlFactory = $curlFactory;
        $this->installRequest = $installRequest;
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

        $curl->post(
            $this->installRequest->getUrl(),
            $this->installRequest->getParams()
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
