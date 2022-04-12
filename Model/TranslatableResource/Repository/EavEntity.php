<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleRepository;
use Aheadworks\Langshop\Model\TranslatableResource\EntityAttribute;
use Aheadworks\Langshop\Model\TranslatableResource\RepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection as CatalogAbstractCollection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\AbstractDb as AbstractCollection;
use Magento\Framework\Data\Collection\AbstractDbFactory as AbstractCollectionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;

class EavEntity implements RepositoryInterface
{
    /**
     * @var EntityAttribute
     */
    private $entityAttribute;

    /**
     * @var LocaleRepository
     */
    private $localeRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var AbstractCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var string
     */
    private $resourceType;

    /**
     * @param EntityAttribute $entityAttribute
     * @param LocaleRepository $localeRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param AbstractCollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param string $resourceType
     */
    public function __construct(
        EntityAttribute $entityAttribute,
        LocaleRepository $localeRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AbstractCollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        string $resourceType
    ) {
        $this->entityAttribute = $entityAttribute;
        $this->localeRepository = $localeRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->resourceType = $resourceType;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): AbstractCollection
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
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('entity_id', $entityId);

        if (!$this->addLocalizedAttributes($collection)->count()) {
            throw new NoSuchEntityException(__('Resource with identifier = "%1" does not exist.', $entityId));
        }

        return $collection->getFirstItem();
    }

    /**
     * @inheritDoc
     */
    public function save(DataObject $entity): DataObject
    {
        //todo: https://aheadworks.atlassian.net/browse/LSM2-56
        return $entity;
    }

    /**
     * Adds localized attribute values to the collection
     *
     * @param AbstractCollection $collection
     * @return AbstractCollection
     * @throws LocalizedException
     */
    private function addLocalizedAttributes(AbstractCollection $collection): AbstractCollection
    {
        if ($collection instanceof CatalogAbstractCollection) {
            $attributeCodes = [[], []];
            foreach ($this->entityAttribute->getList($this->resourceType) as $attribute) {
                $attributeCodes[$attribute->isTranslatable()][] = $attribute->getCode();
            }

            $localizedCollection = clone $collection;
            $collection->addAttributeToSelect($attributeCodes[0]);
            $localizedCollection->addAttributeToSelect($attributeCodes[1]);

            foreach ($this->getLocales() as $locale) {
                $localizedCollection->clear()->setStoreId($locale->getScopeId());

                /** @var AbstractModel $localizedItem */
                foreach ($localizedCollection as $localizedItem) {
                    $item = $collection->getItemById($localizedItem->getId());
                    foreach ($attributeCodes[1] as $attributeCode) {
                        $value[$locale->getLocaleCode()] = $localizedItem->getData($attributeCode);
                        $item->setData($attributeCode, $value);
                    }
                }
            }
        }

        return $collection;
    }

    /**
     * Retrieves available to translate locales
     *
     * @return RecordInterface[]
     */
    private function getLocales(): array
    {
        return $this->localeRepository->getList(
            $this->searchCriteriaBuilder->create()
        )->getItems();
    }
}
