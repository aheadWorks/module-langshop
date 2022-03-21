<?php
namespace Aheadworks\Langshop\Model\Locale\Scope;

use Aheadworks\Langshop\Model\ResourceModel\Locale\Scope\Record as LocaleScopeRecordResourceModel;
use Magento\Framework\Model\AbstractModel;

class Record extends AbstractModel implements RecordInterface
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(LocaleScopeRecordResourceModel::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getRecordId()
    {
        return $this->getData(self::RECORD_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setRecordId($recordId)
    {
        return $this->setData(self::RECORD_ID, $recordId);
    }

    /**
     * {@inheritdoc}
     */
    public function getScopeId()
    {
        return $this->getData(self::SCOPE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setScopeId($scopeId)
    {
        return $this->setData(self::SCOPE_ID, $scopeId);
    }

    /**
     * {@inheritdoc}
     */
    public function getScopeType()
    {
        return $this->getData(self::SCOPE_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setScopeType($scopeType)
    {
        return $this->setData(self::SCOPE_TYPE, $scopeType);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode()
    {
        return $this->getData(self::LOCALE_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setLocaleCode($localeCode)
    {
        return $this->setData(self::LOCALE_CODE, $localeCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsPrimary()
    {
        return $this->getData(self::IS_PRIMARY);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsPrimary($isPrimary)
    {
        return $this->setData(self::IS_PRIMARY, $isPrimary);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Aheadworks\Langshop\Model\Locale\Scope\RecordExtensionInterface $extensionAttributes
    ) {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
