<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data;

interface TranslatableResourceInterface
{
    /**
     * Constants for internal keys
     */
    public const RESOURCE_ID = 'resourceId';
    public const RESOURCE_TYPE = 'resourceType';
    public const FIELDS = 'fields';

    /**
     * Get resource id
     *
     * @return int|null
     */
    public function getResourceId(): ?int;

    /**
     * Set resource id
     *
     * @param int $resourceId
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     */
    public function setResourceId(int $resourceId): TranslatableResourceInterface;

    /**
     * Get resource type
     *
     * @return string|null
     */
    public function getResourceType(): ?string;

    /**
     * Set resource type
     *
     * @param string $resourceType
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     */
    public function setResourceType(string $resourceType): TranslatableResourceInterface;

    /**
     * Get fields
     *
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface[]|null
     */
    public function getFields(): ?array;

    /**
     * Set fields
     *
     * @param \Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface[] $fields
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     */
    public function setFields(array $fields): TranslatableResourceInterface;
}
