<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data;

interface TranslatableResourceInterface
{
    /**
     * Constants for internal keys
     */
    const RESOURCE_ID = 'resourceId';
    const RESOURCE_TYPE = 'resourceType';
    const FIELDS = 'fields';

    /**
     * @return int|null
     */
    public function getResourceId(): ?int;

    /**
     * @param int $resourceId
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     */
    public function setResourceId(int $resourceId): TranslatableResourceInterface;

    /**
     * @return string|null
     */
    public function getResourceType(): ?string;

    /**
     * @param string $resourceType
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     */
    public function setResourceType(string $resourceType): TranslatableResourceInterface;

    /**
     * @return array|null
     */
    public function getFields(): ?array;

    /**
     * @param array $fields
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     */
    public function setFields(array $fields): TranslatableResourceInterface;
}
