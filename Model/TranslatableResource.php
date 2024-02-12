<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model;

use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Magento\Framework\DataObject;

class TranslatableResource extends DataObject implements TranslatableResourceInterface
{
    /**
     * Constants for internal keys
     */
    private const RESOURCE_ID = 'resourceId';
    private const RESOURCE_TYPE = 'resourceType';
    private const RESOURCE_ASSIGNED_LOCALES = 'resourceAssignedLocales';
    private const FIELDS = 'fields';

    /**
     * Get resource id
     *
     * @return string|null
     */
    public function getResourceId(): ?string
    {
        return (string) $this->getData(self::RESOURCE_ID);
    }

    /**
     * Set resource id
     *
     * @param string $resourceId
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     */
    public function setResourceId(string $resourceId): TranslatableResourceInterface
    {
        return $this->setData(self::RESOURCE_ID, $resourceId);
    }

    /**
     * Get resource type
     *
     * @return string|null
     */
    public function getResourceType(): ?string
    {
        return $this->getData(self::RESOURCE_TYPE);
    }

    /**
     * Set resource type
     *
     * @param string $resourceType
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     */
    public function setResourceType(string $resourceType): TranslatableResourceInterface
    {
        return $this->setData(self::RESOURCE_TYPE, $resourceType);
    }

    /**
     * Retrieve locales assigned to resources
     *
     * @return array|null
     */
    public function getResourceAssignedLocales(): ?array
    {
        return $this->getData(self::RESOURCE_ASSIGNED_LOCALES);
    }

    /**
     * Set locales assigned to resources
     *
     * @param array $locales
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     */
    public function setResourceAssignedLocales(array $locales): TranslatableResourceInterface
    {
        return $this->setData(self::RESOURCE_ASSIGNED_LOCALES, $locales);
    }

    /**
     * Get fields
     *
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface[]|null
     */
    public function getFields(): ?array
    {
        return $this->getData(self::FIELDS);
    }

    /**
     * Set fields
     *
     * @param \Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface[] $fields
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     */
    public function setFields(array $fields): TranslatableResourceInterface
    {
        return $this->setData(self::FIELDS, $fields);
    }
}
