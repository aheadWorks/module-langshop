<?php
namespace Aheadworks\Langshop\Model\Locale\Scope\Record;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface
    as LocaleScopeRecordInterface;
use Aheadworks\Langshop\Model\Config;
use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterfaceFactory
    as LocaleScopeRecordInterfaceFactory;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type
    as LocaleScopeTypeSourceModel;

class Generator
{
    /**
     * @var LocaleScopeRecordInterfaceFactory
     */
    private $localeScopeRecordFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param LocaleScopeRecordInterfaceFactory $localeScopeRecordFactory
     * @param Config $config
     */
    public function __construct(
        LocaleScopeRecordInterfaceFactory $localeScopeRecordFactory,
        Config $config
    ) {
        $this->localeScopeRecordFactory = $localeScopeRecordFactory;
        $this->config = $config;
    }

    /**
     * Generate a new locale scope record for the given scope
     *
     * @param string $scopeType
     * @param int $scopeId
     * @return LocaleScopeRecordInterface
     */
    public function generateForScope($scopeType, $scopeId)
    {
        /** @var LocaleScopeRecordInterface $localeScopeRecord */
        $localeScopeRecord = $this->localeScopeRecordFactory->create();

        $localeScopeRecord
            ->setScopeId($scopeId)
            ->setScopeType($scopeType)
            ->setLocaleCode($this->config->getScopeLocaleCode($scopeType, $scopeId))
            //TODO: detect default stores?!
            ->setIsPrimary(false)
        ;

        return $localeScopeRecord;
    }

    /**
     * Generate a new locale scope record for the default scope
     *
     * @return LocaleScopeRecordInterface
     */
    public function generateForDefaultScope()
    {
        /** @var LocaleScopeRecordInterface $localeScopeRecord */
        $localeScopeRecord = $this->localeScopeRecordFactory->create();

        $localeScopeRecord
            //TODO: fake useless id, improve?
            ->setScopeId(0)
            ->setScopeType(LocaleScopeTypeSourceModel::DEFAULT)
            ->setLocaleCode($this->config->getDefaultLocaleCode())
            ->setIsPrimary(true)
        ;

        return $localeScopeRecord;
    }
}
