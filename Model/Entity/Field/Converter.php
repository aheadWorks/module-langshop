<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Field;

use Aheadworks\Langshop\Api\Data\Schema\ResourceInterface;
use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldInterface;
use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldInterfaceFactory;
use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\SortingInterface;
use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\SortingInterfaceFactory;
use Aheadworks\Langshop\Model\Entity\Field;
use Aheadworks\Langshop\Model\Entity\Field\Sorting\DirectionList;
use Magento\Framework\Event\ManagerInterface as EventManager;

class Converter
{
    /**
     * @var DirectionList
     */
    private DirectionList $directionList;

    /**
     * @var FieldInterfaceFactory
     */
    private FieldInterfaceFactory $fieldSchemaFactory;

    /**
     * @var SortingInterfaceFactory
     */
    private SortingInterfaceFactory $sortingElementFactory;

    /**
     * @var EventManager
     */
    private EventManager $eventManager;

    /**
     * @param FieldInterfaceFactory $fieldSchemaFactory
     * @param SortingInterfaceFactory $sortingElementFactory
     * @param EventManager $eventManager
     * @param DirectionList $directionList
     */
    public function __construct(
        FieldInterfaceFactory $fieldSchemaFactory,
        SortingInterfaceFactory $sortingElementFactory,
        EventManager $eventManager,
        DirectionList $directionList
    ) {
        $this->fieldSchemaFactory = $fieldSchemaFactory;
        $this->sortingElementFactory = $sortingElementFactory;
        $this->eventManager = $eventManager;
        $this->directionList = $directionList;
    }

    /**
     * Convert entity fields to resource schema field elements
     *
     * @param Field[] $fields
     * @return array
     */
    public function convert(array $fields = []): array
    {
        $result = [ResourceInterface::FIELDS => [], ResourceInterface::SORTING => []];
        foreach ($fields as $field) {
            $fieldSchema = $this->getFieldSchema($field);
            $sortingElements = $this->getSortingElements($field);

            $this->eventManager->dispatch('aw_ls_schema_field_convert', [
                'sortingElements' => $sortingElements,
                'fieldSchema' => $fieldSchema,
                'field' => $field
            ]);

            $result[ResourceInterface::FIELDS][] = $fieldSchema;
            foreach ($sortingElements as $sortingElement) {
                $result[ResourceInterface::SORTING][] = $sortingElement;
            }
        }

        return $result;
    }

    /**
     * Get field schema
     *
     * @param Field $field
     * @return FieldInterface
     */
    private function getFieldSchema(Field $field): FieldInterface
    {
        return $this->fieldSchemaFactory->create()
            ->setKey($field->getCode())
            ->setLabel($field->getLabel())
            ->setType($field->getType())
            ->setIsTranslatable($field->getIsTranslatable())
            ->setIsTitle($field->getIsTitle())
            ->setFilter($field->getIsFilterable() ? $field->getFilterType() : 'none')
            ->setFilterOptions($field->getFilterOptions() ? $field->getFilterOptions()->toOptionArray() : [])
            ->setSortOrder($field->getSortOrder())
            ->setVisibleOn($field->getVisibleOn());
    }

    /**
     * Get sorting elements
     *
     * @param Field $field
     * @return SortingInterface[]
     */
    private function getSortingElements(Field $field): array
    {
        $sortingElements = [];
        if ($field->getIsSortable()) {
            foreach ($this->directionList->get($field->getType()) as $direction => $labelEnding) {
                $sortingElements[] = $this->sortingElementFactory->create(['data' => [
                        SortingInterface::FIELD => $field->getCode(),
                        SortingInterface::LABEL => $field->getLabel() . ' ' . $labelEnding,
                        SortingInterface::DIRECTION => $direction,
                        SortingInterface::KEY => $field->getCode() . '_' . $direction
                    ]
                ]);
            }
        }

        return $sortingElements;
    }
}
