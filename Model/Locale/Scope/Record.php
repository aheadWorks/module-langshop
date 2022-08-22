<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale\Scope;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Magento\Framework\DataObject;

class Record extends DataObject implements RecordInterface
{
    /**
     * Constants for internal keys
     */
    private const SCOPE_ID = 'scope_id';
    private const SCOPE_TYPE = 'scope_type';
    private const LOCALE_CODE = 'locale_code';
    private const IS_PRIMARY = 'is_primary';

    /**
     * @inheritdoc
     */
    public function getScopeId()
    {
        return $this->getData(self::SCOPE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setScopeId($scopeId)
    {
        return $this->setData(self::SCOPE_ID, $scopeId);
    }

    /**
     * @inheritdoc
     */
    public function getScopeType()
    {
        return $this->getData(self::SCOPE_TYPE);
    }

    /**
     * @inheritdoc
     */
    public function setScopeType($scopeType)
    {
        return $this->setData(self::SCOPE_TYPE, $scopeType);
    }

    /**
     * @inheritdoc
     */
    public function getLocaleCode()
    {
        return $this->getData(self::LOCALE_CODE);
    }

    /**
     * @inheritdoc
     */
    public function setLocaleCode($localeCode)
    {
        return $this->setData(self::LOCALE_CODE, $localeCode);
    }

    /**
     * @inheritdoc
     */
    public function getIsPrimary()
    {
        return $this->getData(self::IS_PRIMARY);
    }

    /**
     * @inheritdoc
     */
    public function setIsPrimary($isPrimary)
    {
        return $this->setData(self::IS_PRIMARY, $isPrimary);
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(
        \Aheadworks\Langshop\Api\Data\Locale\Scope\RecordExtensionInterface $extensionAttributes
    ) {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
