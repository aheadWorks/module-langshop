<?php
namespace Aheadworks\Langshop\Setup\Patch\Data;

use Aheadworks\Langshop\Model\Service\Integration as IntegrationService;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateIntegration implements DataPatchInterface
{
    /**
     * @var IntegrationService
     */
    private $integrationService;

    /**
     * @param IntegrationService $integrationService
     */
    public function __construct(
        IntegrationService $integrationService
    ) {
        $this->integrationService = $integrationService;
    }

    /**
     * Create Langshop integration and generate access token
     *
     * @return void
     * @throws \Exception
     */
    public function apply()
    {
        $this->integrationService->createIntegrationAndGenerateToken();
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }
}
