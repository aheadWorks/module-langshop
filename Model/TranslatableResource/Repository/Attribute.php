<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Source\TranslatableResource\Field;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute;
use Aheadworks\Langshop\Model\TranslatableResource\Field\Pool as FieldPool;
use Aheadworks\Langshop\Model\TranslatableResource\RepositoryInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection;
use Magento\Framework\Data\Collection\AbstractDbFactory as CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDbFactory as ResourceModelFactory;

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
     * @var ResourceModelFactory
     */
    private ResourceModelFactory $resourceModelFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param EntityAttribute $entityAttribute
     * @param CollectionProcessorInterface $collectionProcessor
     * @param FieldPool $fieldPool
     * @param ResourceModelFactory $resourceModelFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        EntityAttribute $entityAttribute,
        CollectionProcessorInterface $collectionProcessor,
        FieldPool $fieldPool,
        ResourceModelFactory $resourceModelFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->entityAttribute = $entityAttribute;
        $this->collectionProcessor = $collectionProcessor;
        $this->fieldPool = $fieldPool;
        $this->resourceModelFactory = $resourceModelFactory;
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
        //todo https://aheadworks.atlassian.net/browse/LSM2-76
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
            $this->prepareLocalizedCollection($localizedCollection, $locale->getScopeId());
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

    /**
     * Prepare localized collection
     *
     * @param Collection $collection
     * @param int $storeId
     * @return void
     * @throws \Zend_Db_Select_Exception
     */
    private function prepareLocalizedCollection(Collection $collection, int $storeId): void
    {
        $from = $collection->getSelect()->getPart('from');
        // unset removes the previous store label join
        unset($from['al']);
        $collection->getSelect()->setPart('from', $from);
        $collection->clear()->addStoreLabel($storeId);
    }
}
