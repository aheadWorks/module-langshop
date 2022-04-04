<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterfaceFactory;
use Aheadworks\Langshop\Model\Entity\Pool as EntityPool;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class Converter
{
    /**
     * @var EntityPool
     */
    private $entityPool;

    /**
     * @var FieldInterfaceFactory
     */
    private $fieldFactory;

    /**
     * @var TranslatableResourceInterfaceFactory
     */
    private $resourceFactory;

    /**
     * @param EntityPool $entityPool
     * @param FieldInterfaceFactory $fieldFactory
     * @param TranslatableResourceInterfaceFactory $resourceFactory
     */
    public function __construct(
        EntityPool $entityPool,
        FieldInterfaceFactory $fieldFactory,
        TranslatableResourceInterfaceFactory $resourceFactory
    ) {
        $this->entityPool = $entityPool;
        $this->fieldFactory = $fieldFactory;
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * Converts the model to translatable resource
     *
     * @param DataObject $item
     * @param string $resourceType
     * @return TranslatableResourceInterface
     * @throws LocalizedException
     */
    public function convert(
        DataObject $item,
        string $resourceType
    ): TranslatableResourceInterface {
        return $this->resourceFactory->create()
            ->setResourceId((int) $item->getId())
            ->setResourceType($resourceType)
            ->setFields($this->getResourceFields($item, $resourceType));
    }

    /**
     * @param DataObject $item
     * @param string $resourceType
     * @return FieldInterface[]
     * @throws LocalizedException
     */
    private function getResourceFields(
        DataObject $item,
        string $resourceType
    ): array {
        $fields = [];

        $entityFields = $this->entityPool->getByType($resourceType)->getFields();
        foreach ($entityFields as $entityField) {
            $fields[] = $this->fieldFactory->create()
                ->setKey($entityField->getCode())
                ->setValue($item->getData($entityField->getCode()));
        }

        return $fields;
    }
}
