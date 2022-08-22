<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data;

interface TranslatableResourceInterface
{
    /**
     * Get resource id
     *
     * @return string|null
     */
    public function getResourceId(): ?string;

    /**
     * Set resource id
     *
     * @param string $resourceId
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     */
    public function setResourceId(string $resourceId): TranslatableResourceInterface;

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
