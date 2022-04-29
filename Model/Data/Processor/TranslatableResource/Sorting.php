<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource;

use Aheadworks\Langshop\Api\Data\Schema\ResourceInterface;
use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\SortingInterface;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\Entity\Field\Converter as FieldConverter;
use Aheadworks\Langshop\Model\Schema\TranslatableResource\Sorting as SchemaSorting;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Sorting implements ProcessorInterface
{
    /**
     * @var EntityAttributeProvider
     */
    private EntityAttributeProvider $entityAttributeProvider;

    /**
     * @var SortOrderBuilder
     */
    private SortOrderBuilder $sortOrderBuilder;

    /**
     * @var FieldConverter
     */
    private FieldConverter $fieldConverter;

    /**
     * @param EntityAttributeProvider $entityAttributeProvider
     * @param SortOrderBuilder $sortOrderBuilder
     * @param FieldConverter $fieldConverter
     */
    public function __construct(
        EntityAttributeProvider $entityAttributeProvider,
        SortOrderBuilder $sortOrderBuilder,
        FieldConverter $fieldConverter
    ) {
        $this->entityAttributeProvider = $entityAttributeProvider;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->fieldConverter = $fieldConverter;
    }

    /**
     * Prepares sorting for search criteria
     *
     * @param array $data
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function process(array $data): array
    {
        $sortBy = $data['sortBy'] ?? null;
        $data['sortBy'] = [];

        if ($sortBy) {
            $sorting = $this->getSorting($data['resourceType'], $sortBy);
            if (!$sorting) {
                throw new NoSuchEntityException(__('Sorting "%1" is not correct.', $sortBy));
            }

            /** @var SchemaSorting $sorting */
            $data['sortBy'][] = $this->sortOrderBuilder
                ->setField($sorting->getField())
                ->setDirection(strtoupper($sorting->getDirection()))
                ->create();
        }

        return $data;
    }

    /**
     * @param string $resourceType
     * @param string $sortBy
     * @return SortingInterface|null
     * @throws LocalizedException
     */
    private function getSorting(string $resourceType, string $sortBy): ?SortingInterface
    {
        $sortings = $this->fieldConverter->convert(
            $this->entityAttributeProvider->getList($resourceType)
        )[ResourceInterface::SORTING];

        /** @var SortingInterface $sorting */
        foreach ($sortings as $sorting) {
            if ($sorting->getKey() === $sortBy) {
                return $sorting;
            }
        }

        return null;
    }
}
