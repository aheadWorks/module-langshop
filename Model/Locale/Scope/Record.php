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
     * Get scope id
     *
     * @return int
     */
    public function getScopeId(): int
    {
        return $this->getData(self::SCOPE_ID);
    }

    /**
     * Set scope id
     *
     * @param int $scopeId
     * @return $this
     */
    public function setScopeId(int $scopeId): RecordInterface
    {
        return $this->setData(self::SCOPE_ID, $scopeId);
    }

    /**
     * Get scope type
     *
     * @return string
     */
    public function getScopeType(): string
    {
        return $this->getData(self::SCOPE_TYPE);
    }

    /**
     * Set scope type
     *
     * @param string $scopeType
     * @return $this
     */
    public function setScopeType(string $scopeType): RecordInterface
    {
        return $this->setData(self::SCOPE_TYPE, $scopeType);
    }

    /**
     * Get locale code
     *
     * @return string
     */
    public function getLocaleCode(): string
    {
        return $this->getData(self::LOCALE_CODE);
    }

    /**
     * Set locale code
     *
     * @param string $localeCode
     * @return $this
     */
    public function setLocaleCode(string $localeCode): RecordInterface
    {
        return $this->setData(self::LOCALE_CODE, $localeCode);
    }

    /**
     * Get is primary locale flag
     *
     * @return bool
     */
    public function getIsPrimary(): bool
    {
        return $this->getData(self::IS_PRIMARY);
    }

    /**
     * Set is primary locale flag
     *
     * @param bool $isPrimary
     * @return $this
     */
    public function setIsPrimary(bool $isPrimary): RecordInterface
    {
        return $this->setData(self::IS_PRIMARY, $isPrimary);
    }
}
