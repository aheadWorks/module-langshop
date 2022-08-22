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
    private const FIELDS = 'fields';

    /**
     * @inheritDoc
     */
    public function getResourceId(): ?string
    {
        return (string) $this->getData(self::RESOURCE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setResourceId(string $resourceId): TranslatableResourceInterface
    {
        return $this->setData(self::RESOURCE_ID, $resourceId);
    }

    /**
     * @inheritDoc
     */
    public function getResourceType(): ?string
    {
        return $this->getData(self::RESOURCE_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setResourceType(string $resourceType): TranslatableResourceInterface
    {
        return $this->setData(self::RESOURCE_TYPE, $resourceType);
    }

    /**
     * @inheritDoc
     */
    public function getFields(): ?array
    {
        return $this->getData(self::FIELDS);
    }

    /**
     * @inheritDoc
     */
    public function setFields(array $fields): TranslatableResourceInterface
    {
        return $this->setData(self::FIELDS, $fields);
    }
}
