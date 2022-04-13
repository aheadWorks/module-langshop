<?php
namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource;

use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\Entity\Field\Filter\Builder;
use Aheadworks\Langshop\Model\TranslatableResource\EntityAttribute;
use Magento\Framework\Exception\LocalizedException;

class Filter implements ProcessorInterface
{
    /**
     * @var Builder
     */
    private $filterBuilder;

    /**
     * @var EntityAttribute
     */
    private $entityAttribute;

    /**
     * @param Builder $filterBuilder
     * @param EntityAttribute $entityAttribute
     */
    public function __construct(
        Builder $filterBuilder,
        EntityAttribute $entityAttribute
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->entityAttribute = $entityAttribute;
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
        $attributes = $this->entityAttribute->getList($data['resourceType']);

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
