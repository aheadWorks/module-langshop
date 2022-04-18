<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\LocaleScope as LocaleScopeProvider;
use Aheadworks\Langshop\Model\TranslatableResource\RepositoryInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Attribute as AttributeValidation;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Locale as LocaleValidation;
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
     * @var LocaleValidation
     */
    private LocaleValidation $localeValidation;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var AttributeValidation
     */
    private AttributeValidation $attributeValidation;

    /**
     * @var LocaleScopeProvider
     */
    private LocaleScopeProvider $localeScopeProvider;

    /**
     * @var ResourceModelFactory
     */
    private ResourceModelFactory $resourceModelFactory;

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
     * @param LocaleValidation $localeValidation
     * @param CollectionFactory $collectionFactory
     * @param AttributeValidation $attributeValidation
     * @param LocaleScopeProvider $localeScopeProvider
     * @param ResourceModelFactory $resourceModelFactory
     * @param EntityAttributeProvider $entityAttributeProvider
     * @param CollectionProcessorInterface $collectionProcessor
     * @param string $resourceType
     */
    public function __construct(
        LocaleValidation $localeValidation,
        CollectionFactory $collectionFactory,
        AttributeValidation $attributeValidation,
        LocaleScopeProvider $localeScopeProvider,
        ResourceModelFactory $resourceModelFactory,
        EntityAttributeProvider $entityAttributeProvider,
        CollectionProcessorInterface $collectionProcessor,
        string $resourceType
    ) {
        $this->localeValidation = $localeValidation;
        $this->collectionFactory = $collectionFactory;
        $this->attributeValidation = $attributeValidation;
        $this->localeScopeProvider = $localeScopeProvider;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->entityAttributeProvider = $entityAttributeProvider;
        $this->collectionProcessor = $collectionProcessor;
        $this->resourceType = $resourceType;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria, array $locales): Collection
    {
        foreach ($locales as $locale) {
            $this->localeValidation->validate($locale);
        }

        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        return $this->addLocalizedAttributes($collection, $locales);
    }

    /**
     * @inheritDoc
     */
    public function get(int $entityId, array $locales): DataObject
    {
        foreach ($locales as $locale) {
            $this->localeValidation->validate($locale);
        }

        $collection = $this->prepareCollectionById($entityId);

        return $this->addLocalizedAttributes($collection, $locales)->getFirstItem();
    }

    /**
     * @inheritDoc
     */
    public function save(int $entityId, array $translations): void
    {
        $translationByLocales = [];
        foreach ($translations as $translation) {
            $this->localeValidation->validate($translation->getLocale());
            $this->attributeValidation->validate($translation->getKey(), $this->resourceType);

            $translationByLocales[$translation->getLocale()][$translation->getKey()] = $translation->getValue();
        }

        /** @var AbstractModel $item */
        $item = $this->prepareCollectionById($entityId)->getFirstItem();
        foreach ($translationByLocales as $locale => $values) {
            foreach ($this->getLocaleScopes([$locale]) as $localeScope) {
                $item->addData($values)->setData('store_id', $localeScope->getScopeId());
                $this->resourceModelFactory->create()->save($item);
            }
        }
    }

    /**
     * Retrieves locale scopes by locale codes or primary flag
     *
     * @param string[] $locales
     * @return RecordInterface[]
     */
    private function getLocaleScopes(array $locales): array
    {
        $localeScopes = $this->localeScopeProvider->getList();

        $searchByLocales = fn (RecordInterface $localeScope): bool => in_array($localeScope->getLocaleCode(), $locales);
        $searchByPrimary = fn (RecordInterface $localeScope): bool => (bool) $localeScope->getIsPrimary();

        return array_filter($localeScopes, $searchByLocales) ?:
            array_filter($localeScopes, $searchByPrimary);
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
     * @param string[] $locales
     * @return Collection
     * @throws LocalizedException
     */
    private function addLocalizedAttributes(Collection $collection, array $locales): Collection
    {
        if ($collection instanceof CatalogCollection) {
            $attributeCodes = [
                'translatable' => [],
                'untranslatable' => []
            ];
            foreach ($this->entityAttributeProvider->getList($this->resourceType) as $attribute) {
                $isTranslatable = $attribute->isTranslatable() ? 'translatable' : 'untranslatable';
                $attributeCodes[$isTranslatable][] = $attribute->getCode();
            }

            $localizedCollection = clone $collection;
            $collection->addAttributeToSelect($attributeCodes['untranslatable']);
            $localizedCollection->addAttributeToSelect($attributeCodes['translatable']);

            foreach ($this->getLocaleScopes($locales) as $localeScope) {
                $localizedCollection->clear()->setStoreId($localeScope->getScopeId());

                /** @var AbstractModel $localizedItem */
                foreach ($localizedCollection as $localizedItem) {
                    $item = $collection->getItemById($localizedItem->getId());
                    foreach ($attributeCodes['translatable'] as $attributeCode) {
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
