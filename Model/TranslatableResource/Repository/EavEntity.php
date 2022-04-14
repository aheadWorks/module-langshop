<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Model\TranslatableResource\EntityAttribute as EntityAttributeProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\LocaleScope as LocaleScopeProvider;
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
     * @var LocaleScopeProvider
     */
    private LocaleScopeProvider $localeScopeProvider;

    /**
     * @var ResourceModelFactory
     */
    private ResourceModelFactory $resourceModelFactory;

    /**
     * @var TranslationValidation
     */
    private TranslationValidation $translationValidation;

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
     * @param LocaleScopeProvider $localeScopeProvider
     * @param ResourceModelFactory $resourceModelFactory
     * @param TranslationValidation $translationValidation
     * @param EntityAttributeProvider $entityAttributeProvider
     * @param CollectionProcessorInterface $collectionProcessor
     * @param string $resourceType
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        LocaleScopeProvider $localeScopeProvider,
        ResourceModelFactory $resourceModelFactory,
        TranslationValidation $translationValidation,
        EntityAttributeProvider $entityAttributeProvider,
        CollectionProcessorInterface $collectionProcessor,
        string $resourceType
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->localeScopeProvider = $localeScopeProvider;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->translationValidation = $translationValidation;
        $this->entityAttributeProvider = $entityAttributeProvider;
        $this->collectionProcessor = $collectionProcessor;
        $this->resourceType = $resourceType;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Collection
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        return $this->addLocalizedAttributes($collection);
    }

    /**
     * @inheritDoc
     */
    public function get(int $entityId): DataObject
    {
        $collection = $this->prepareCollectionById($entityId);

        return $this->addLocalizedAttributes($collection)->getFirstItem();
    }

    /**
     * @inheritDoc
     */
    public function save(int $entityId, array $translations): void
    {
        $localeScopes = [];
        foreach ($this->localeScopeProvider->getList() as $localeScope) {
            $localeScopes[$localeScope->getLocaleCode()] = $localeScope->getScopeId();
        }

        $translationByScopes = [];
        foreach ($translations as $translation) {
            $this->translationValidation->validate($translation, $this->resourceType);

            if (isset($localeScopes[$translation->getLocale()])) {
                $scopeId = $localeScopes[$translation->getLocale()];
                $translationByScopes[$scopeId][$translation->getKey()] = $translation->getValue();
            }
        }

        /** @var AbstractModel $item */
        $item = $this->prepareCollectionById($entityId)->getFirstItem();
        foreach ($translationByScopes as $scopeId => $values) {
            $item->addData($values)->setData('store_id', $scopeId);
            $this->resourceModelFactory->create()->save($item);
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
     * @return Collection
     * @throws LocalizedException
     */
    private function addLocalizedAttributes(Collection $collection): Collection
    {
        if ($collection instanceof CatalogCollection) {
            $attributeCodes = [[], []];
            foreach ($this->entityAttributeProvider->getList($this->resourceType) as $attribute) {
                $attributeCodes[$attribute->isTranslatable()][] = $attribute->getCode();
            }

            $localizedCollection = clone $collection;
            $collection->addAttributeToSelect($attributeCodes[0]);
            $localizedCollection->addAttributeToSelect($attributeCodes[1]);

            foreach ($this->localeScopeProvider->getList() as $localeScope) {
                $localizedCollection->clear()->setStoreId($localeScope->getScopeId());

                /** @var AbstractModel $localizedItem */
                foreach ($localizedCollection as $localizedItem) {
                    $item = $collection->getItemById($localizedItem->getId());
                    foreach ($attributeCodes[1] as $attributeCode) {
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
