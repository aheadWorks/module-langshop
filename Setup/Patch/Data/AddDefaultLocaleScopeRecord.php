<?php
namespace Aheadworks\Langshop\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Generator
    as LocaleScopeRecordGenerator;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository
    as LocaleScopeRecordRepository;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

//TODO: consider implementing Revertable interface
class AddDefaultLocaleScopeRecord implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var LocaleScopeRecordGenerator
     */
    private $localeScopeRecordGenerator;

    /**
     * @var LocaleScopeRecordRepository
     */
    private $localeScopeRecordRepository;

    /**
     * @param LocaleScopeRecordGenerator $localeScopeRecordGenerator
     * @param LocaleScopeRecordRepository $localeScopeRecordRepository
     */
    public function __construct(
        LocaleScopeRecordGenerator $localeScopeRecordGenerator,
        LocaleScopeRecordRepository $localeScopeRecordRepository
    ) {
        $this->localeScopeRecordGenerator = $localeScopeRecordGenerator;
        $this->localeScopeRecordRepository = $localeScopeRecordRepository;
    }

    /**
     * Create and save locale scope record of the default scope
     *
     * @return $this
     * @throws LocalizedException
     */
    public function apply()
    {
        $defaultLocaleScopeRecord = $this->localeScopeRecordGenerator->generateForDefaultScope();
        $this->localeScopeRecordRepository->save($defaultLocaleScopeRecord);

        return $this;
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

    /**
     * Specify version to trigger the patch for preventing double execution
     *
     * @return string
     */
    public static function getVersion()
    {
        return '1.0.0';
    }
}
