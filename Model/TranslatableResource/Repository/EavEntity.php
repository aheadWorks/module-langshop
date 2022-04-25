<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Source\TranslatableResource\Field;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Aheadworks\Langshop\Model\TranslatableResource\RepositoryInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Translation as TranslationValidation;
use Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection as CatalogCollection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\AbstractDb as Collection;
use Magento\Framework\Data\Collection\AbstractDbFactory as CollectionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDbFactory as ResourceModelFactory;

class EavEntity implements RepositoryInterface
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var ResourceModelFactory
     */
    private ResourceModelFactory $resourceModelFactory;

    /**
     * @var TranslationValidation
     */
    private TranslationValidation $translationValidation;

    /**
     * @var LocaleScopeRepository
     */
    private LocaleScopeRepository $localeScopeRepository;

    /**
     * @var EntityAttributeProvider
     */
    private EntityAttributeProvider $entityAttributeProvider;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var string
     */
    private string $resourceType;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ResourceModelFactory $resourceModelFactory
     * @param TranslationValidation $translationValidation
     * @param LocaleScopeRepository $localeScopeRepository
     * @param EntityAttributeProvider $entityAttributeProvider
     * @param CollectionProcessorInterface $collectionProcessor
     * @param string $resourceType
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ResourceModelFactory $resourceModelFactory,
        TranslationValidation $translationValidation,
        LocaleScopeRepository $localeScopeRepository,
        EntityAttributeProvider $entityAttributeProvider,
        CollectionProcessorInterface $collectionProcessor,
        string $resourceType
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->translationValidation = $translationValidation;
        $this->localeScopeRepository = $localeScopeRepository;
        $this->entityAttributeProvider = $entityAttributeProvider;
        $this->collectionProcessor = $collectionProcessor;
        $this->resourceType = $resourceType;
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
    public function get(int $entityId, array $localeScopes): DataObject
    {
        $collection = $this->prepareCollectionById($entityId);

        return $this->addLocalizedAttributes($collection, $localeScopes)->getFirstItem();
    }

    /**
     * @inheritDoc
     */
    public function save(int $entityId, array $translations): void
    {
        $translationByLocales = [];
        foreach ($translations as $translation) {
            $this->translationValidation->validate($translation, $this->resourceType);
            $translationByLocales[$translation->getLocale()][$translation->getKey()] = $translation->getValue();
        }

        /** @var AbstractModel $item */
        $item = $this->prepareCollectionById($entityId)->getFirstItem();
        foreach ($translationByLocales as $locale => $values) {
            foreach ($this->localeScopeRepository->getByLocale([$locale]) as $localeScope) {
                $item->addData($values)->setData('store_id', $localeScope->getScopeId());
                $this->resourceModelFactory->create()->save($item);
            }
        }
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
            ->addFieldToFilter('entity_id', $entityId);

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
     */
    private function addLocalizedAttributes(Collection $collection, array $localeScopes): Collection
    {
        if ($collection instanceof CatalogCollection) {
            $attributeCodes = [
                Field::TRANSLATABLE => [],
                Field::UNTRANSLATABLE => []
            ];
            foreach ($this->entityAttributeProvider->getList($this->resourceType) as $attribute) {
                $isTranslatable = $attribute->isTranslatable() ? Field::TRANSLATABLE : Field::UNTRANSLATABLE;
                $attributeCodes[$isTranslatable][] = $attribute->getCode();
            }

            $localizedCollection = clone $collection;
            $collection->addAttributeToSelect($attributeCodes[Field::UNTRANSLATABLE]);
            $localizedCollection->addAttributeToSelect($attributeCodes[Field::TRANSLATABLE]);

            foreach ($localeScopes as $localeScope) {
                $localizedCollection->clear()->setStoreId($localeScope->getScopeId());

                /** @var AbstractModel $localizedItem */
                foreach ($localizedCollection as $localizedItem) {
                    $item = $collection->getItemById($localizedItem->getId());
                    foreach ($attributeCodes[Field::TRANSLATABLE] as $attributeCode) {
                        $value = is_array($item->getData($attributeCode))
                            ? $item->getData($attributeCode)
                            : [];
                        $value[$localeScope->getLocaleCode()] = $localizedItem->getData($attributeCode);
                        $item->setData($attributeCode, $value);
                    }
                }
            }
        }

        return $collection;
    }
}
