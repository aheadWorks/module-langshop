<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource;

use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\Entity\Field\Filter\Builder;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Magento\Framework\Exception\LocalizedException;

class Filter implements ProcessorInterface
{
    /**
     * @var Builder
     */
    private Builder $filterBuilder;

    /**
     * @var EntityAttributeProvider
     */
    private EntityAttributeProvider $entityAttributeProvider;

    /**
     * @param Builder $filterBuilder
     * @param EntityAttributeProvider $entityAttributeProvider
     */
    public function __construct(
        Builder $filterBuilder,
        EntityAttributeProvider $entityAttributeProvider
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->entityAttributeProvider = $entityAttributeProvider;
    }

    /**
     * Create filters
     *
     * @param array $data
     * @return array
     * @throws LocalizedException
     */
    public function process($data)
    {
        $filter = $data['filter'] ?? [];
        $filters = [];
        $attributes = $this->entityAttributeProvider->getList($data['resourceType']);

        foreach ($filter as $field => $value) {
            $attribute = $attributes[$field] ?? null;
            //todo: throw an exception if field is missing
            if ($attribute) {
                $filterType = $attribute->getFilterType();
                $filters[] = $this->filterBuilder->create($field, $value, $filterType);
            }
        }
        $data['filters'] = $filters;

        return $data;
    }
}
