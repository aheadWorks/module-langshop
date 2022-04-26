<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Source\TranslatableResource\Field;
use Aheadworks\Langshop\Model\TranslatableResource\Field\ProcessorInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Aheadworks\Langshop\Model\TranslatableResource\Field\Pool as FieldPool;
use Aheadworks\Langshop\Model\TranslatableResource\RepositoryInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Translation as TranslationValidation;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection;
use Magento\Framework\Data\Collection\AbstractDbFactory as CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavAttribute;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;

class Attribute implements RepositoryInterface
{
    /**
     * @var EntityAttribute
     */
    private EntityAttribute $entityAttribute;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var FieldPool
     */
    private FieldPool $fieldPool;

    /**
     * @var TranslationValidation
     */
    private TranslationValidation $translationValidation;

    /**
     * @var LocaleScopeRepository
     */
    private LocaleScopeRepository $localeScopeRepository;

    /**
     * @var ProcessorInterface
     */
    private ProcessorInterface $fieldProcessor;

    /**
     * @var AttributeFactory
     */
    private AttributeFactory $attributeFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param EntityAttribute $entityAttribute
     * @param CollectionProcessorInterface $collectionProcessor
     * @param FieldPool $fieldPool
     * @param TranslationValidation $translationValidation
     * @param LocaleScopeRepository $localeScopeRepository
     * @param ProcessorInterface $fieldProcessor
     * @param AttributeFactory $attributeFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        EntityAttribute $entityAttribute,
        CollectionProcessorInterface $collectionProcessor,
        FieldPool $fieldPool,
        TranslationValidation $translationValidation,
        LocaleScopeRepository $localeScopeRepository,
        ProcessorInterface $fieldProcessor,
        AttributeFactory $attributeFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->entityAttribute = $entityAttribute;
        $this->collectionProcessor = $collectionProcessor;
        $this->fieldPool = $fieldPool;
        $this->translationValidation = $translationValidation;
        $this->localeScopeRepository = $localeScopeRepository;
        $this->fieldProcessor = $fieldProcessor;
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * @inheritDoc
     */
    public function get(int $entityId, array $localeScopes): DataObject
    {
        $collection = $this->prepareCollectionById($entityId);

        return $this->addLocalizedAttributes($collection, $localeScopes)->getFirstItem();
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria, array $localeScopes): Collection
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        return $this->addLocalizedAttributes($collection, $localeScopes);
    }

    /**
     * @inheritDoc
     */
    public function save(int $entityId, array $translations): void
    {
        $translationByLocales = [];
        foreach ($translations as $translation) {
            $this->translationValidation->validate($translation, 'attribute');
            $translationByLocales[$translation->getLocale()][$translation->getKey()] = $translation->getValue();
        }

        $item = $this->getItem($entityId);
        foreach ($translationByLocales as $locale => $values) {
            foreach ($this->localeScopeRepository->getByLocale([$locale]) as $localeScope) {
                $item->setStoreId($localeScope->getScopeId());
                $values = $this->fieldProcessor->process($item, $values);
                $item->addData($values);
                $item->save();
            }
        }
    }

    /**
     * Get item
     *
     * @param int $entityId
     * @return EavAttribute
     */
    private function getItem(int $entityId)
    {
        $attribute = $this->attributeFactory->create();
        return $attribute->load($entityId);
    }

    /**
     * Filters collection by incoming id, throws exception if nothing is found
     *
     * @param int $entityId
     * @return Collection
     * @throws NoSuchEntityException
     */
    private function prepareCollectionById(int $entityId): Collection
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('main_table.attribute_id', $entityId);

        if (!$collection->getSize()) {
            throw new NoSuchEntityException(__('Resource with identifier = "%1" does not exist.', $entityId));
        }

        return $collection;
    }

    /**
     * Adds localized attribute values to the collection
     *
     * @param Collection $collection
     * @param RecordInterface[] $localeScopes
     * @return Collection
     * @throws LocalizedException
     * @throws \Zend_Db_Exception
     */
    private function addLocalizedAttributes(Collection $collection, array $localeScopes): Collection
    {
        $attributeCodes = [
            Field::TRANSLATABLE => [],
            Field::UNTRANSLATABLE => []
        ];
        foreach ($this->entityAttribute->getList('attribute') as $attribute) {
            $isTranslatable = $attribute->isTranslatable() ? Field::TRANSLATABLE : Field::UNTRANSLATABLE;
            $attributeCodes[$isTranslatable][] = $attribute->getCode();
        }

        $localizedCollection = clone $collection;

        foreach ($localeScopes as $locale) {
            foreach ($localizedCollection as $localizedItem) {
                $localizedItem->setStoreId($locale->getScopeId());
                $item = $collection->getItemById($localizedItem->getId());
                foreach ($attributeCodes[Field::TRANSLATABLE] as $attributeCode) {
                    $field = $this->fieldPool->getField($attributeCode);
                    $value = is_array($item->getData($attributeCode))
                        ? $item->getData($attributeCode)
                        : [];
                    $value[$locale->getLocaleCode()] = $field->getData($localizedItem, $attributeCode);
                    $item->setData($attributeCode, $value);
                }
            }
        }

        return $collection;
    }
}
