<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleRepository;
use Aheadworks\Langshop\Model\TranslatableResource\EntityAttribute;
use Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection as CatalogAbstractCollection;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Eav\Model\Entity\Collection\AbstractCollectionFactory;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;

class EavEntity
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
     * @var string
     */
    private $resourceType;

    /**
     * @param EntityAttribute $entityAttribute
     * @param LocaleRepository $localeRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param AbstractCollectionFactory $collectionFactory
     * @param string $resourceType
     */
    public function __construct(
        EntityAttribute $entityAttribute,
        LocaleRepository $localeRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AbstractCollectionFactory $collectionFactory,
        string $resourceType
    ) {
        $this->entityAttribute = $entityAttribute;
        $this->localeRepository = $localeRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionFactory = $collectionFactory;
        $this->resourceType = $resourceType;
    }

    /**
     * Prepares/gets collection for the entity
     *
     * @param SearchCriteria $searchCriteria
     * @return AbstractCollection
     * @throws LocalizedException
     */
    public function getList(SearchCriteria $searchCriteria): AbstractCollection
    {
        $collection = $this->collectionFactory->create();

        return $this->addLocalizedAttributes($collection);
    }

    /**
     * Retrieves resource data by the identifier
     *
     * @param int $resourceId
     * @return DataObject
     * @throws LocalizedException
     */
    public function getById(int $resourceId): DataObject
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('entity_id', $resourceId);

        if (!$this->addLocalizedAttributes($collection)->count()) {
            throw new NoSuchEntityException(__('Resource with identifier = "%1" does not exist.', $resourceId));
        }

        return $collection->getFirstItem();
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
                    foreach ($attributeCodes[1] as $attributeCode) {
                        $item = $collection->getItemById($localizedItem->getId());

                        $value = $item->getData($attributeCode) ?? [];
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
