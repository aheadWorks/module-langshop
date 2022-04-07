<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Model\Entity\Field;
use Aheadworks\Langshop\Model\Entity\Pool as EntityPool;
use Magento\Framework\Exception\LocalizedException;

class EntityAttribute
{
    /**
     * @var EntityPool
     */
    private $entityPool;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @param EntityPool $entityPool
     */
    public function __construct(
        EntityPool $entityPool
    ) {
        $this->entityPool = $entityPool;
    }

    /**
     * Retrieves entity attributes by specific type
     *
     * @param string $entityType
     * @return Field[]
     * @throws LocalizedException
     */
    public function getList(string $entityType): array
    {
        if (!isset($this->attributes[$entityType])) {
            $this->attributes[$entityType] = $this->entityPool->getByType($entityType)->getFields();
        }

        return $this->attributes[$entityType];
    }
}
