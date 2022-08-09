<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FilterInterface;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\Entity\Field\Filter\Builder as FilterBuilder;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Attribute as AttributeValidation;
use Magento\Framework\Exception\LocalizedException;

class Filter implements ProcessorInterface
{
    /**
     * @var EntityAttributeProvider
     */
    private EntityAttributeProvider $entityAttributeProvider;

    /**
     * @var AttributeValidation
     */
    private AttributeValidation $attributeValidation;

    /**
     * @var FilterBuilder
     */
    private FilterBuilder $filterBuilder;

    /**
     * @param EntityAttributeProvider $entityAttributeProvider
     * @param AttributeValidation $attributeValidation
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        EntityAttributeProvider $entityAttributeProvider,
        AttributeValidation $attributeValidation,
        FilterBuilder $filterBuilder
    ) {
        $this->entityAttributeProvider = $entityAttributeProvider;
        $this->attributeValidation = $attributeValidation;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Prepares filters for search criteria
     *
     * @param array $data
     * @return array
     * @throws LocalizedException
     */
    public function process(array $data): array
    {
        $filters = $data['filter'] ?? [];
        $data['filter'] = [];

        if ($filters) {
            $attributes = $this->entityAttributeProvider->getList($data['resourceType']);

            /** @var FilterInterface $filter */
            foreach ($filters as $filter) {
                $this->attributeValidation->validate($filter->getField(), $data['resourceType']);

                $attribute = $attributes[$filter->getField()] ?? null;
                if ($attribute) {
                    $data['filter'][] = $this->filterBuilder->create(
                        $filter->getField(),
                        $filter->getValue(),
                        $attribute->getFilterType()
                    );
                }
            }
        }

        return $data;
    }
}
