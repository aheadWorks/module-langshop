<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model;

use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Magento\Framework\Model\AbstractModel;

class TranslatableResource extends AbstractModel implements TranslatableResourceInterface
{
    /**
     * @inheritDoc
     */
    public function getResourceId(): ?int
    {
        return (int) $this->getData(self::RESOURCE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setResourceId(int $resourceId): TranslatableResourceInterface
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
