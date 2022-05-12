<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class Saas
{
    private const XML_PATH_GENERAL_SAAS_PUBLIC_KEY = 'aw_ls/general/saas_public_key';
    private const XML_PATH_GENERAL_SAAS_DOMAIN = 'aw_ls/general/saas_domain';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var WriterInterface
     */
    private WriterInterface $configWriter;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $configWriter
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
    }

    /**
     * Retrieve Langshop Saas public key
     *
     * @return string|null
     */
    public function getPublicKey(): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_SAAS_PUBLIC_KEY
        );
    }

    /**
     * Save Saas public key
     *
     * @param string $publicKey
     * @return void
     */
    public function savePublicKey(string $publicKey): void
    {
        $this->configWriter->save(
            self::XML_PATH_GENERAL_SAAS_PUBLIC_KEY,
            $publicKey
        );
    }

    /**
     * Get Langshop Saas domain
     *
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_SAAS_DOMAIN
        );
    }
}
