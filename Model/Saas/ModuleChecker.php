<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas;

use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;

class ModuleChecker
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
     * Checks if the connector has been configured already
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        return (bool) $this->saasConfig->getPublicKey();
    }
}
