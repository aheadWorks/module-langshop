<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data;

interface StatusInterface
{
    /**
     * Constants for internal keys
     */
    public const STATUS_ID = 'status_id';
    public const RESOURCE_TYPE = 'resource_type';
    public const RESOURCE_ID = 'resource_id';
    public const STORE_ID = 'store_id';
    public const STATUS = 'status';

    /**
     * Status values
     */
    public const STATUS_NOT_TRANSLATED = 0;
    public const STATUS_TRANSLATED = 1;
    public const STATUS_PROCESSING = 2;

    /**
     * Set status id
     *
     * @param int $statusId
     * @return $this
     */
    public function setStatusId(int $statusId): StatusInterface;

    /**
     * Get status id
     *
     * @return int
     */
    public function getStatusId(): int;

    /**
     * Set resource type
     *
     * @param string $resourceType
     * @return $this
     */
    public function setResourceType(string $resourceType): StatusInterface;

    /**
     * Get resource type
     *
     * @return string
     */
    public function getResourceType(): string;

    /**
     * Set resource id
     *
     * @param int $resourceId
     * @return $this
     */
    public function setResourceId(int $resourceId): StatusInterface;

    /**
     * Get resource id
     *
     * @return int
     */
    public function getResourceId(): int;

    /**
     * Set store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): StatusInterface;

    /**
     * Get store id
     *
     * @return int
     */
    public function getStoreId(): int;

    /**
     * Set status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): StatusInterface;

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus(): int;
}
