<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas;

use Aheadworks\Langshop\Model\Saas\Request\Install as InstallRequest;
use Magento\Framework\Exception\IntegrationException;

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
     * @param CurlSender $curlSender
     * @param InstallRequest $installRequest
     */
    public function __construct(
        CurlSender $curlSender,
        InstallRequest $installRequest
    ) {
        $this->curlSender = $curlSender;
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
        $curl = $this->curlSender->post(
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
