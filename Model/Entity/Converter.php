<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity;

use Aheadworks\Langshop\Api\Data\Schema\ResourceInterface;
use Aheadworks\Langshop\Api\Data\Schema\ResourceInterfaceFactory;
use Aheadworks\Langshop\Model\Entity;
use Aheadworks\Langshop\Model\Entity\Field\Converter as FieldConverter;
use Magento\Framework\Exception\LocalizedException;

class Converter
{
    /**
     * @var FieldConverter
     */
    private FieldConverter $fieldConverter;

    /**
     * @var ResourceInterfaceFactory
     */
    private ResourceInterfaceFactory $resourceFactory;

    /**
     * @param FieldConverter $fieldConverter
     * @param ResourceInterfaceFactory $resourceFactory
     */
    public function __construct(
        FieldConverter $fieldConverter,
        ResourceInterfaceFactory $resourceFactory
    ) {
        $this->fieldConverter = $fieldConverter;
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * Convert entity to schema resource model
     *
     * @param Entity $entity
     * @return ResourceInterface
     * @throws LocalizedException
     */
    public function convert(Entity $entity): ResourceInterface
    {
        $fieldsElements = $this->fieldConverter->convert($entity->getFields());

        return $this->resourceFactory->create(['data' => [
                ResourceInterface::RESOURCE => $entity->getResourceType(),
                ResourceInterface::LABEL => $entity->getLabel(),
                ResourceInterface::DESCRIPTION => $entity->getDescription(),
                ResourceInterface::ICON => $entity->getIcon(),
                ResourceInterface::VIEW_TYPE => $entity->getViewType(),
                ResourceInterface::FIELDS => $fieldsElements[ResourceInterface::FIELDS],
                ResourceInterface::SORTING => $fieldsElements[ResourceInterface::SORTING]
            ]
        ]);
    }

    /**
     * Convert entities to schema resource models
     *
     * @param Entity[] $entities
     * @return ResourceInterface[]
     * @throws LocalizedException
     */
    public function convertAll(array $entities = []): array
    {
        $resources = [];
        foreach ($entities as $entity) {
            $resources[] = $this->convert($entity);
        }

        return $resources;
    }
}