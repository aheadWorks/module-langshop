<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\Locale\Scope;

interface RecordInterface
{
    /**
     * Get scope id
     *
     * @return int
     */
    public function getScopeId(): int;

    /**
     * Set scope id
     *
     * @param int $scopeId
     * @return $this
     */
    public function setScopeId(int $scopeId): RecordInterface;

    /**
     * Get scope type
     *
     * @return string
     */
    public function getScopeType(): string;

    /**
     * Set scope type
     *
     * @param string $scopeType
     * @return $this
     */
    public function setScopeType(string $scopeType): RecordInterface;

    /**
     * Get locale code
     *
     * @return string
     */
    public function getLocaleCode(): string;

    /**
     * Set locale code
     *
     * @param string $localeCode
     * @return $this
     */
    public function setLocaleCode(string $localeCode): RecordInterface;

    /**
     * Get is primary locale flag
     *
     * @return bool
     */
    public function getIsPrimary(): bool;

    /**
     * Set is primary locale flag
     *
     * @param bool $isPrimary
     * @return $this
     */
    public function setIsPrimary(bool $isPrimary): RecordInterface;
}
