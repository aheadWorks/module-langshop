<?php
namespace Aheadworks\Langshop\Model\Config\Backend\Locale\Scope;

use Magento\Framework\App\Config\Value as ConfigValue;
use Aheadworks\Langshop\Model\Config\Backend\Locale\Scope\ListToTranslate\Persister
    as LocaleScopeListToTranslatePersister;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Cache\TypeListInterface as CacheTypeListInterface;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

//TODO: consider using observer/plugin instead of custom backend model
class ListToTranslate extends ConfigValue
{
    /**
     * @var LocaleScopeListToTranslatePersister
     */
    private $localeScopeListToTranslatePersister;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param CacheTypeListInterface $cacheTypeList
     * @param LocaleScopeListToTranslatePersister $localeScopeListToTranslatePersister
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        CacheTypeListInterface $cacheTypeList,
        LocaleScopeListToTranslatePersister $localeScopeListToTranslatePersister,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->localeScopeListToTranslatePersister = $localeScopeListToTranslatePersister;
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Get old value from the scope record repository instead of config
     *
     * @return string
     */
    public function getOldValue()
    {
        return $this->localeScopeListToTranslatePersister->getSerializedScopeUidList();
    }

    /**
     * Replace the config value with the actual list of scope records
     *
     * @return $this
     */
    public function afterLoad()
    {
        $this->setValue(
            $this->localeScopeListToTranslatePersister->getSerializedScopeUidList()
        );
        return parent::afterLoad();
    }

    /**
     * Save the selected scopes as the scope record list
     *
     * @return $this
     */
    public function afterSave()
    {
        parent::afterSave();
        $this->localeScopeListToTranslatePersister->saveSerializedScopeUidList(
            $this->getValue()
        );
        return $this;
    }
}
