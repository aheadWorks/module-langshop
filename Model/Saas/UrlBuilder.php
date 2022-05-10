<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\Saas;

use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;
use Aheadworks\Langshop\Model\Service\Integration;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\Store;

class UrlBuilder
{
    /**
     * @var CurlFactory
     */
    private CurlFactory $curlFactory;

    /**
     * @var Store
     */
    private Store $store;

    /**
     * @var Integration
     */
    private Integration $integration;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $jsonSerializer;

    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var SaasConfig
     */
    private SaasConfig $config;

    /**
     * @param CurlFactory $curlFactory
     * @param Store $store
     * @param Integration $integration
     * @param SerializerInterface $jsonSerializer
     * @param Session $session
     * @param SaasConfig $config
     */
    public function __construct(
        CurlFactory $curlFactory,
        Store $store,
        Integration $integration,
        SerializerInterface $jsonSerializer,
        Session $session,
        SaasConfig $config
    ) {
        $this->curlFactory = $curlFactory;
        $this->store = $store;
        $this->integration = $integration;
        $this->jsonSerializer = $jsonSerializer;
        $this->session = $session;
        $this->config = $config;
    }


    /**
     * Get wizard url
     *
     * @return string
     * @throws IntegrationException
     */
    public function getWizardUrl(): string
    {
        $params = [
            'domain' => str_replace(
                ['http://', 'https://'],
                '',
                $this->store->getBaseUrl()
            ),
            'email' => $this->session->getUser()->getEmail(),
            'token' => $this->integration->getAccessToken(),
            'rest_path' => '/rest/all/V1/awLangshop'
        ];
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
     * todo: when Saas will be work set actual url
     * @return string
     */
    public function getApplicationUrl(): string
    {
        return 'https://langshop.io/';
    }
}
