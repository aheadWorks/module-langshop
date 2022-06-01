<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Field\Collector;

use Aheadworks\Langshop\Model\Entity\Field\CollectorInterface;
use Aheadworks\Langshop\Model\Entity\FieldFactory as EntityFieldFactory;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class DynamicAttribute implements CollectorInterface
{
    /**
     * @const array
     */
    private const TRANSLATABLE_TYPES = [
        'text',
        'textarea'
    ];

    /**
     * @var string[]
     */
    private array $blacklist;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var EntityFieldFactory
     */
    private EntityFieldFactory $entityFieldFactory;

    /**
     * @var string
     */
    private string $entityTypeCode;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param EntityFieldFactory $entityFieldFactory
     * @param string $entityTypeCode
     * @param array $blacklist
     */
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        EntityFieldFactory $entityFieldFactory,
        string $entityTypeCode,
        array $blacklist = []
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->entityFieldFactory = $entityFieldFactory;
        $this->entityTypeCode = $entityTypeCode;
        $this->blacklist = $blacklist;
    }

    /**
     * @inheritDoc
     */
    public function collect(array $fields = []): array
    {
        /**
         * @todo the building of the search criteria has to be reconsidered
         * in terms of being able to compile different filters for entity types
         * or ignore some service attributes
         */
        $attributes = $this->attributeRepository->getList(
            $this->entityTypeCode,
            $this->searchCriteriaBuilder->create()
        )->getItems();

        /** @var Attribute $attribute */
        foreach ($attributes as $attribute) {
            $code = $attribute->getAttributeCode();
            if (!isset($fields[$code]) && $this->isTranslatable($attribute)) {
                $field = $this->entityFieldFactory->create()
                    ->setCode($code)
                    ->setLabel($attribute->getDefaultFrontendLabel())
                    ->setType($attribute->getFrontendInput())
                    ->setIsTranslatable($this->isTranslatable($attribute))
                    ->setIsFilterable($attribute->getIsFilterable())
                    ->setFilterType($attribute->getFrontendInput());

                $fields[$code] = $field;
            }
        }

        return $fields;
    }

    /**
     * Checks if the attribute is available for translate
     *
     * @param Attribute $attribute
     * @return bool
     */
    private function isTranslatable(Attribute $attribute): bool
    {
        /**
         * @todo the list of translatable types can differ regarding of
         * used entity type, that probably has to come from di
         */
        return !$attribute->isScopeGlobal() &&
            in_array($attribute->getFrontendInput(), self::TRANSLATABLE_TYPES) &&
            !in_array($attribute->getAttributeCode(), $this->blacklist);
    }
}
