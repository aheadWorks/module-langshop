<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\SaasManagementInterface;
use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;

class Saas implements SaasManagementInterface
{
    /**
     * @var SaasConfig
     */
    private SaasConfig $saasConfig;

    /**
     * @param SaasConfig $saasConfig
     */
    public function __construct(
        SaasConfig $saasConfig
    ) {
        $this->saasConfig = $saasConfig;
    }

    /**
     * Save public key
     *
     * @param string $publicKey
     * @return bool
     */
    public function saveKey(string $publicKey): bool
    {
        $this->saasConfig->savePublicKey($publicKey);

        return true;
    }
}
