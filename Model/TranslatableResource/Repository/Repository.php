<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionInterface;
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
    private EntityAttributeProvider $attributeProvider;

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
     * @param EntityAttributeProvider $attributeProvider
     * @param CollectionProcessorInterface $collectionProcessor
     * @param string $resourceType
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ResourceModelFactory $resourceModelFactory,
        TranslationValidation $translationValidation,
        EventManagerInterface $eventManager,
        LocaleScopeRepository $localeScopeRepository,
        EntityAttributeProvider $attributeProvider,
        CollectionProcessorInterface $collectionProcessor,
        string $resourceType
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->translationValidation = $translationValidation;
        $this->eventManager = $eventManager;
        $this->localeScopeRepository = $localeScopeRepository;
        $this->attributeProvider = $attributeProvider;
        $this->collectionProcessor = $collectionProcessor;
        $this->resourceType = $resourceType;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria, array $localeScopes): Collection
    {
        /** @var Collection|CollectionInterface $collection */
        $collection = $this->collectionFactory->create();
        $collection->setResourceType($this->resourceType);

        $this->collectionProcessor->process($searchCriteria, $collection);

        return $this->addLocalizedAttributes($collection, $localeScopes);
    }

    /**
     * @inheritDoc
     */
    public function get(string $entityId, array $localeScopes): DataObject
    {
        $collection = $this->prepareCollectionById($entityId);

        return $this->addLocalizedAttributes($collection, $localeScopes)->getFirstItem();
    }

    /**
     * @inheritDoc
     */
    public function save(string $entityId, array $translations): void
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
     * @param string $entityId
     * @return Collection
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function prepareCollectionById(string $entityId): Collection
    {
        /** @var CatalogCollection|CollectionInterface $collection */
        $collection = $this->collectionFactory->create();
        $collection->setResourceType($this->resourceType);

        $fieldName = $collection->getResource()->getIdFieldName();
        $collection->addFieldToFilter($fieldName, $entityId);

        if ($collection instanceof CatalogCollection) {
            $collection->addAttributeToSelect(
                $this->attributeProvider->getCodesOfUntranslatableFields($this->resourceType)
            );
        }
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
        $translatableAttributeCodes = $this->attributeProvider->getCodesOfTranslatableFields($this->resourceType);
        $untranslatableAttributeCodes = $this->attributeProvider->getCodesOfUntranslatableFields($this->resourceType);

        /** @var CatalogCollection|CollectionInterface $localizedCollection */
        $localizedCollection = clone $collection;

        if ($collection instanceof CatalogCollection) {
            $collection->addAttributeToSelect($untranslatableAttributeCodes);
            $localizedCollection->addAttributeToSelect($translatableAttributeCodes);
        }

        foreach ($localeScopes as $localeScope) {
            $localizedCollection->setStoreId((int) $localeScope->getScopeId())->clear();

            /** @var AbstractModel $localizedItem */
            foreach ($localizedCollection->getItems() as $localizedItem) {
                $item = $collection->getItemById($localizedItem->getId());
                foreach ($translatableAttributeCodes as $attributeCode) {
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
