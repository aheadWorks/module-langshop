<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Csv\Model;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection as CsvCollection;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\CollectionFactory;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\ProcessorInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class Csv implements RepositoryInterface
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var EntityAttributeProvider
     */
    private EntityAttributeProvider $attributeProvider;

    /**
     * @var ProcessorInterface
     */
    private ProcessorInterface $collectionProcessor;

    /**
     * @param EntityAttributeProvider $attributeProvider
     * @param ProcessorInterface $collectionProcessor
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        EntityAttributeProvider $attributeProvider,
        ProcessorInterface $collectionProcessor,
        CollectionFactory $collectionFactory
    ) {
        $this->attributeProvider = $attributeProvider;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria, array $localeScopes): Collection
    {
        /** @var CsvCollection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        return $this->addLocalizedAttributes($collection, $localeScopes);
    }

    /**
     * @inheritDoc
     */
    public function get(string $entityId, array $localeScopes): DataObject
    {
        /** @var CsvCollection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addEntityIdFilter($entityId);

        return $this->addLocalizedAttributes($collection, $localeScopes)->getFirstItem();
    }

    /**
     * @inheritDoc
     */
    //phpcs:ignore
    public function save(string $entityId, array $translations): void
    {
        // TODO: https://aheadworks.atlassian.net/browse/LSM2-172
    }

    /**
     * Adds localized attribute values to the collection
     *
     * @param CsvCollection $collection
     * @param RecordInterface[] $localeScopes
     * @return Collection
     * @throws LocalizedException
     * @throws \Exception
     */
    private function addLocalizedAttributes(CsvCollection $collection, array $localeScopes): Collection
    {
        $localizedCollection = clone $collection;
        $translatableAttributeCodes = $this->attributeProvider->getCodesOfTranslatableFields('csv');
        $localizedCollection->setIsNeedToAddLinesAttribute(true);

        foreach ($localeScopes as $localeScope) {
            $localizedCollection->setStoreId((int)$localeScope->getScopeId())->clear();
            /** @var Model[] $localizedItems */
            $localizedItems = $localizedCollection->getItems();

            foreach ($localizedItems as $localizedItem) {
                /** @var Model $item */
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
        $collection->setSize($localizedCollection->getSize());

        return $collection;
    }
}
