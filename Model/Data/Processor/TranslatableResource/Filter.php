<?php
namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource;

use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\Entity\Field\Filter\Pool as FilterPool;
use Aheadworks\Langshop\Model\TranslatableResource\EntityAttribute;
use Magento\Framework\Exception\LocalizedException;

class Filter implements ProcessorInterface
{
    /**
     * @var FilterPool
     */
    private $filterPool;

    /**
     * @var EntityAttribute
     */
    private $entityAttribute;

    /**
     * @param FilterPool $filterPool
     * @param EntityAttribute $entityAttribute
     */
    public function __construct(
        FilterPool $filterPool,
        EntityAttribute $entityAttribute
    ) {
        $this->filterPool = $filterPool;
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
            $attribute = $attributes[$field];
            $filterType = $attribute->getFilterType();
            $filterObject = $this->filterPool->get($filterType);

            $filters[] = $filterObject->create($field, $value);
        }
        $data['filters'] = $filters;

        return $data;
    }
}
