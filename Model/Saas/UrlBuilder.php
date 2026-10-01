<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas;

use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;
use Aheadworks\Langshop\Model\Saas\Request\Install as InstallRequest;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\UrlInterface;

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
     * @var UrlInterface
     */
    private UrlInterface $url;

    /**
     * @param SaasConfig $saasConfig
     * @param CurlSender $curlSender
     * @param InstallRequest $installRequest
     * @param SerializerInterface $serializer
     * @param UrlInterface $url
     */
    public function __construct(
        SaasConfig $saasConfig,
        CurlSender $curlSender,
        InstallRequest $installRequest,
        SerializerInterface $serializer,
        UrlInterface $url
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

        $url = $body['url'] ?? '';

        return $this->isAllowedSaasUrl($url) ? $url : '';
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
     * Hosts allowed for the embedded Langshop application
     *
     * @return string[]
     */
    public function getAllowedHosts(): array
    {
        $hosts = ['langshop.io'];
        $configuredHost = $this->extractHost((string) $this->saasConfig->getDomain());
        if ($configuredHost) {
            $hosts[] = $configuredHost;
        }

        return array_values(array_unique($hosts));
    }

    /**
     * Check whether the URL belongs to the configured Langshop application
     *
     * @param string $url
     * @return bool
     */
    public function isAllowedSaasUrl(string $url): bool
    {
        //phpcs:ignore Magento2.Functions.DiscouragedFunction
        $parts = parse_url($url);
        if (!is_array($parts) || empty($parts['scheme']) || empty($parts['host'])) {
            return false;
        }
        if (!in_array(strtolower((string) $parts['scheme']), ['http', 'https'], true)) {
            return false;
        }

        $host = strtolower((string) $parts['host']);
        foreach ($this->getAllowedHosts() as $allowed) {
            if ($host === $allowed || str_ends_with($host, '.' . $allowed)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract hostname from a URL or host string
     *
     * @param string $url
     * @return string|null
     */
    private function extractHost(string $url): ?string
    {
        if ($url === '') {
            return null;
        }
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }
        //phpcs:ignore Magento2.Functions.DiscouragedFunction
        $host = parse_url($url, PHP_URL_HOST);

        return is_string($host) && $host !== '' ? strtolower($host) : null;
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

    /**
     * Get app builder app page
     *
     * @return string
     */
    public function getAppBuilderAppPage(): string
    {
        return $this->url->getUrl('adminhtml/dashboard');
    }
}
