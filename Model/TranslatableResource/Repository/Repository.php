<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Aheadworks\Langshop\Model\Source\TranslatableResource\Field;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Translation as TranslationValidation;
use Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection as CatalogCollection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\AbstractDb as Collection;
use Magento\Framework\Data\Collection\AbstractDbFactory as CollectionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDbFactory as ResourceModelFactory;

class Repository implements RepositoryInterface
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
     * @var EventManagerInterface
     */
    private EventManagerInterface $eventManager;

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
     * @param EventManagerInterface $eventManager
     * @param LocaleScopeRepository $localeScopeRepository
     * @param EntityAttributeProvider $entityAttributeProvider
     * @param CollectionProcessorInterface $collectionProcessor
     * @param string $resourceType
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ResourceModelFactory $resourceModelFactory,
        TranslationValidation $translationValidation,
        EventManagerInterface $eventManager,
        LocaleScopeRepository $localeScopeRepository,
        EntityAttributeProvider $entityAttributeProvider,
        CollectionProcessorInterface $collectionProcessor,
        string $resourceType
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->translationValidation = $translationValidation;
        $this->eventManager = $eventManager;
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
        $resourceModel = $this->resourceModelFactory->create();
        $translationByLocales = [];

        foreach ($translations as $translation) {
            $this->translationValidation->validate($translation, $this->resourceType);
            $translationByLocales[$translation->getLocale()][$translation->getKey()] = $translation->getValue();
        }

        foreach ($translationByLocales as $locale => $values) {
            /** @var AbstractModel $item */
            $item = $this->prepareCollectionById($entityId)
                ->getFirstItem()
                ->addData($values);

            foreach ($this->localeScopeRepository->getByLocale([$locale]) as $localeScope) {
                $item->setData('store_id', $localeScope->getScopeId());
                $resourceModel->save($item);

                $this->eventManager->dispatch('aw_ls_save_translatable_resource', [
                    'resource_type' => $this->resourceType,
                    'resource_id' => $entityId,
                    'store_id' => $localeScope->getScopeId()
                ]);
            }
        }
    }

    /**
     * Filters collection by incoming id, throws exception if nothing is found
     *
     * @param int $entityId
     * @return Collection
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function prepareCollectionById(int $entityId): Collection
    {
        $collection = $this->collectionFactory->create();

        $fieldName = $collection->getResource()->getIdFieldName();
        $collection->addFieldToFilter($fieldName, (string) $entityId);

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
        $attributeCodes = [
            Field::TRANSLATABLE => [],
            Field::UNTRANSLATABLE => []
        ];
        foreach ($this->entityAttributeProvider->getList($this->resourceType) as $attribute) {
            $isTranslatable = $attribute->isTranslatable() ? Field::TRANSLATABLE : Field::UNTRANSLATABLE;
            if ($isTranslatable === Field::TRANSLATABLE || $attribute->isNecessary()) {
                $attributeCodes[$isTranslatable][] = $attribute->getCode();
            }
        }

        /** @var CatalogCollection $localizedCollection */
        $localizedCollection = clone $collection;

        if ($collection instanceof CatalogCollection) {
            $collection->addAttributeToSelect($attributeCodes[Field::UNTRANSLATABLE]);
            $localizedCollection->addAttributeToSelect($attributeCodes[Field::TRANSLATABLE]);
        }

        foreach ($localeScopes as $localeScope) {
            $localizedCollection->setStoreId((int) $localeScope->getScopeId())->clear();

            /** @var AbstractModel $localizedItem */
            foreach ($localizedCollection->getItems() as $localizedItem) {
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

        return $collection;
    }
}
