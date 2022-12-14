<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Setup\Patch\Data;

use Aheadworks\Langshop\Model\Service\Integration as IntegrationService;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class CreateIntegration implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var IntegrationService
     */
    private IntegrationService $integrationService;

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
     * @return $this
     * @throws IntegrationException
     */
    public function apply()
    {
        $this->integrationService->createIntegration();
        $this->integrationService->generateToken();

        return $this;
    }

    /**
     * Remove access token
     *
     * @return void
     * @throws IntegrationException
     */
    public function revert()
    {
        $this->integrationService->deleteIntegration();
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
