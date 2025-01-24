<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data;

interface ResourceBindingInterface
{
    /**
     * Constants for internal keys
     */
    public const ID = 'id';
    public const RESOURCE_TYPE = 'resource_type';
    public const ORIGINAL_RESOURCE_ID = 'original_resource_id';
    public const TRANSLATED_RESOURCE_ID = 'translated_resource_id';
    public const STORE_ID = 'store_id';

    public const CMS_BLOCK_RESOURCE_TYPE = 'cms_block';
    public const CMS_PAGE_RESOURCE_TYPE = 'cms_page';
    public const AGREEMENT_RESOURCE_TYPE = 'agreement';

    /**
     * Get resource type
     *
     * @return string
     */
    public function getResourceType(): string;

    /**
     * Set resource type
     *
     * @param string $resourceType
     * @return $this
     */
    public function setResourceType(string $resourceType): self;

    /**
     * Get original resource ID
     *
     * @return int
     */
    public function getOriginalResourceId(): int;

    /**
     * Set original resource ID
     *
     * @param int $resourceId
     * @return $this
     */
    public function setOriginalResourceId(int $resourceId): self;

    /**
     * Get translated resource ID
     *
     * @return int
     */
    public function getTranslatedResourceId(): int;

    /**
     * Set original resource ID
     *
     * @param int $resourceId
     * @return $this
     */
    public function setTranslatedResourceId(int $resourceId): self;

    /**
     * Get store ID
     *
     * @return int
     */
    public function getStoreId(): int;

    /**
     * Set store ID
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): self;
}
