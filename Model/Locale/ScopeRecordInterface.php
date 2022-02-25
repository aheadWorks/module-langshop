<?php
namespace Aheadworks\Langshop\Model\Locale;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ScopeRecordInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    const RECORD_ID = 'record_id';
    const SCOPE_ID = 'scope_id';
    const SCOPE_TYPE = 'scope_type';
    const LOCALE_CODE = 'locale_code';
    const IS_PRIMARY = 'is_primary';
    /**#@-*/

    /**
     * Get record id
     *
     * @return int
     */
    public function getRecordId();

    /**
     * Set record id
     *
     * @param int $recordId
     * @return $this
     */
    public function setRecordId($recordId);

    /**
     * Get scope id
     *
     * @return int
     */
    public function getScopeId();

    /**
     * Set scope id
     *
     * @param int $scopeId
     * @return $this
     */
    public function setScopeId($scopeId);

    /**
     * Get scope type
     *
     * @return string
     */
    public function getScopeType();

    /**
     * Set scope type
     *
     * @param string $scopeType
     * @return $this
     */
    public function setScopeType($scopeType);

    /**
     * Get locale code
     *
     * @return string
     */
    public function getLocaleCode();

    /**
     * Set locale code
     *
     * @param string $localeCode
     * @return $this
     */
    public function setLocaleCode($localeCode);

    /**
     * Get is primary locale flag
     *
     * @return bool
     */
    public function getIsPrimary();

    /**
     * Set is primary locale flag
     *
     * @param bool $isPrimary
     * @return $this
     */
    public function setIsPrimary($isPrimary);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Langshop\Model\Locale\ScopeRecordExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Langshop\Model\Locale\ScopeRecordExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Langshop\Model\Locale\ScopeRecordExtensionInterface $extensionAttributes
    );
}
