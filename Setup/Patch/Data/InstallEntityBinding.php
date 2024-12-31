<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Aheadworks\Langshop\Model\Entity\Binding\InitialSetup\CmsBlock;

class InstallEntityBinding implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @param CmsBlock $cmsBlock
     */
    public function __construct(
        private readonly CmsBlock $cmsBlock
    ) {
    }

    /**
     * Install binding initial data
     *
     * @return $this
     * @throws LocalizedException
     */
    public function apply(): self
    {
        $this->cmsBlock->installInitialData();

        return $this;
    }

    /**
     * Revert patch
     */
    public function revert(): bool
    {
       return true;
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * @return string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
