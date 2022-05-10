<?php
namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\SaasManagementInterface;
use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;

class Saas implements SaasManagementInterface
{
    /**
     * @var SaasConfig
     */
    private SaasConfig $config;

    /**
     * @param SaasConfig $config
     */
    public function __construct(
        SaasConfig $config
    ) {
        $this->config = $config;
    }

    /**
     * Save public key
     *
     * @param string $publicKey
     * @return bool
     */
    public function saveKey(string $publicKey): bool
    {
        $this->config->savePublicKey($publicKey);
        return true;
    }
}
