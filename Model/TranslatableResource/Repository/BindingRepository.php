<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Magento\Framework\Data\Collection\AbstractDb as Collection;
use Magento\Framework\Data\Collection\AbstractDbFactory as CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDbFactory as ResourceModelFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\Store;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Translation as TranslationValidation;
use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding as BindingResource;
use \Aheadworks\Langshop\Model\Entity\Binding\Manager as BindingManager;

class BindingRepository implements RepositoryInterface
{
    /**
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param BindingResource $bindingResource
     * @param ResourceModelFactory $resourceModelFactory
     * @param TranslationValidation $translationValidation
     * @param EventManagerInterface $eventManager
     * @param LocaleScopeRepository $localeScopeRepository
     * @param EntityAttributeProvider $attributeProvider
     * @param string $resourceType
     * @param string $fieldToDuplicateByScope
     */
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly BindingResource $bindingResource,
        private readonly ResourceModelFactory $resourceModelFactory,
        private readonly TranslationValidation $translationValidation,
        private readonly EventManagerInterface $eventManager,
        private readonly LocaleScopeRepository $localeScopeRepository,
        private readonly EntityAttributeProvider $attributeProvider,
        private readonly string $resourceType,
        private readonly string $fieldToDuplicateByScope
    ) {
    }

    /**
     * Retrieve entities matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param RecordInterface[] $localeScopes
     * @return Collection
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria, array $localeScopes): Collection
    {
        /** @var AbstractCollection $collection */
        $collection = $this->collectionFactory->create();
        $localizedCollection = $this->collectionFactory->create();

        $collection->getSelect()
            ->joinInner(
                ['binding_tbl' => $this->bindingResource->getTable(BindingResource::MAIN_TABLE_NAME)],
                $this->getBindingTableJoinCondition(ResourceBindingInterface::ORIGINAL_RESOURCE_ID, Store::DEFAULT_STORE_ID),
                []
            );
        $this->collectionProcessor->process($searchCriteria, $collection);
        $collection = $this->addAlwaysOriginalAttribute($collection);

        return $this->addLocalizedAttributes($collection, $localizedCollection, $localeScopes);
    }

    /**
     * Get entity
     *
     * @param string $entityId
     * @param RecordInterface[] $localeScopes
     * @return DataObject
     * @throws LocalizedException
     */
    public function get(string $entityId, array $localeScopes): DataObject
    {
        $collection = $this->prepareCollectionById($entityId, false);
        $localizedCollection = $this->prepareCollectionById($entityId, true);

        return $this->addLocalizedAttributes($collection, $localizedCollection, $localeScopes)->getFirstItem();
    }

    /**
     * Filters collection by incoming id, throws exception if nothing is found
     *
     * @param string $entityId
     * @param bool $isLocalized
     * @return Collection
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function prepareCollectionById(string $entityId, bool $isLocalized): Collection
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        if ($isLocalized) {
            $collection
                ->getSelect()
                ->where('binding_tbl.' . ResourceBindingInterface::ORIGINAL_RESOURCE_ID . ' = ?', $entityId);
        } else {
            $fieldName = $collection->getResource()->getIdFieldName();
            $collection->addFieldToFilter($fieldName, $entityId);
            $collection->getSelect()
                ->joinInner(
                    ['binding_tbl' => $this->bindingResource->getTable(BindingResource::MAIN_TABLE_NAME)],
                    $this->getBindingTableJoinCondition(ResourceBindingInterface::ORIGINAL_RESOURCE_ID, Store::DEFAULT_STORE_ID),
                    []
                );

            if (!$collection->getSize()) {
                throw new NoSuchEntityException(__('Resource with identifier = "%1" does not exist.', $entityId));
            }
        }

        return $collection;
    }

    /**
     * Save entity
     *
     * @param string $entityId
     * @param TranslationInterface[] $translations
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function save(string $entityId, array $translations): void
    {
        $resourceModel = $this->resourceModelFactory->create();
        $translationByLocales = [];
        $collection = $this->prepareCollectionById($entityId, false);
        $originalItem = $collection->getFirstItem();

        foreach ($translations as $translation) {
            $this->translationValidation->validate($translation, $this->resourceType);
            $value = $translation->getValue() ?? false;
            $translationByLocales[$translation->getLocale()][$translation->getKey()] = $value;
        }

        foreach ($translationByLocales as $locale => $values) {
            foreach ($this->localeScopeRepository->getByLocale([$locale]) as $localeScope) {
                /** @var Collection $localizedCollection */
                $localizedCollection = clone $this->collectionFactory->create();
                $localizedCollection->getSelect()
                    ->joinInner(
                        ['binding_tbl' => $this->bindingResource->getTable(BindingResource::MAIN_TABLE_NAME)],
                        $this->getBindingTableJoinCondition(ResourceBindingInterface::TRANSLATED_RESOURCE_ID, $localeScope->getScopeId()),
                        [ResourceBindingInterface::TRANSLATED_RESOURCE_ID]
                    )->where(ResourceBindingInterface::ORIGINAL_RESOURCE_ID . ' = ?', $entityId);

                $item = $localizedCollection->getFirstItem();
                if ($item->getId()) {
                    $isToRemove = true;
                    foreach ($values as $value) {
                        if ($value) {
                            $isToRemove = false;
                            break;
                        }
                    }
                    if ($isToRemove) {
                        $resourceModel->delete($item);
                    } else {
                        $item
                            ->addData($values)
                            ->setData(BindingManager::BINDING_MODE, BindingManager::BINDING_SKIP);
                        $resourceModel->save($item);
                    }
                } else {
                    $bindingData = [
                        ResourceBindingInterface::ORIGINAL_RESOURCE_ID => $originalItem->getId(),
                        ResourceBindingInterface::STORE_ID => $localeScope->getScopeId()
                    ];
                    $originalItem
                        ->setData($this->fieldToDuplicateByScope, $originalItem->getData($this->fieldToDuplicateByScope) . ' (' . $locale . ')')
                        ->setData(BindingManager::BINDING_MODE, BindingManager::BINDING_CREATE)
                        ->setData(BindingManager::BINDING_DATA, $bindingData)
                        ->addData($values)
                        ->setId(null)
                        ->setStoreId([$localeScope->getScopeId()]);
                    $resourceModel->save($originalItem);
                }

                $this->eventManager->dispatch('aw_ls_save_translatable_resource', [
                    'resource_type' => $this->resourceType,
                    'resource_id' => $entityId,
                    'store_id' => $localeScope->getScopeId()
                ]);
            }
        }
    }

    /**
     * Get binding table join condition
     *
     * @param string $fieldToJoin
     * @param int $storeId
     * @return string
     */
    private function getBindingTableJoinCondition(string $fieldToJoin, int $storeId): string
    {
        return sprintf(
            'main_table.block_id = binding_tbl.%s AND resource_type = "%s" AND binding_tbl.store_id = %s',
            $fieldToJoin,
            $this->resourceType,
            $storeId
        );
    }

    /**
     * Adds always original attribute values to the collection
     *
     * @param Collection $collection
     * @return Collection
     * @throws LocalizedException
     */
    private function addAlwaysOriginalAttribute(Collection $collection): Collection
    {
        $alwaysOriginalFields = $this->attributeProvider->getAlwaysOriginalFields($this->resourceType);

        foreach ($collection->getItems() as $item) {
            foreach ($alwaysOriginalFields as $field) {
                $item->setData($field->getCode(), $item->getData($field->getOriginalCode()));
            }
        }

        return $collection;
    }

    /**
     * Adds localized attribute values to the collection
     *
     * @param Collection $collection
     * @param Collection $originalLocalizedCollection
     * @param RecordInterface[] $localeScopes
     * @return Collection
     * @throws LocalizedException
     */
    private function addLocalizedAttributes(Collection $collection, Collection $originalLocalizedCollection, array $localeScopes): Collection
    {
        $translatableAttributeCodes = $this->attributeProvider->getCodesOfTranslatableFields($this->resourceType);
        foreach ($localeScopes as $localeScope) {
            $localizedCollection = clone $originalLocalizedCollection;
            $localizedCollection->getSelect()
                ->joinInner(
                    ['binding_tbl' => $this->bindingResource->getTable(BindingResource::MAIN_TABLE_NAME)],
                    $this->getBindingTableJoinCondition(ResourceBindingInterface::TRANSLATED_RESOURCE_ID, $localeScope->getScopeId()),
                    [ResourceBindingInterface::ORIGINAL_RESOURCE_ID]
                );

            $localizedItems = $localizedCollection->getItems();
            foreach ($collection->getItems() as $item) {
                $localizedItem = null;
                foreach ($localizedItems as $localizedItemToFind) {
                    if ($localizedItemToFind->getData(ResourceBindingInterface::ORIGINAL_RESOURCE_ID) == $item->getId()) {
                        $localizedItem = $localizedItemToFind;
                        break;
                    }
                }

                foreach ($translatableAttributeCodes as $attributeCode) {
                    $value = is_array($item->getData($attributeCode))
                        ? $item->getData($attributeCode)
                        : [];
                    $value[$localeScope->getLocaleCode()] = $localizedItem?->getData($attributeCode);
                    $item->setData($attributeCode, $value);
                }
            }
        }

        return $collection;
    }
}
