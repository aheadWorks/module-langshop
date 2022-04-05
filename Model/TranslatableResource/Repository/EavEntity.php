<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Entity\Pool as EntityPool;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleRepository;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Eav\Model\Entity\Collection\AbstractCollectionFactory;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class EavEntity
{
    /**
     * @var EntityPool
     */
    private $entityPool;

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
     * @param EntityPool $entityPool
     * @param LocaleRepository $localeRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param AbstractCollectionFactory $collectionFactory
     * @param string $resourceType
     */
    public function __construct(
        EntityPool $entityPool,
        LocaleRepository $localeRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AbstractCollectionFactory $collectionFactory,
        string $resourceType
    ) {
        $this->entityPool = $entityPool;
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

        return $this->joinAttributes($collection);
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
        $this->joinAttributes($collection);

        if (!$collection->count()) {
            throw new NoSuchEntityException(__('Resource with identifier = "%1" does not exist.', $resourceId));
        }

        return $collection->getFirstItem();
    }

    /**
     * Joins available for translate attributes to the collection
     *
     * @param AbstractCollection $collection
     * @return AbstractCollection
     * @throws LocalizedException
     */
    private function joinAttributes(AbstractCollection $collection): AbstractCollection
    {
        $attributes = $this->getAttributes();
        if ($attributes) {
            foreach ($this->getLocales() as $locale) {
                foreach ($attributes as $attribute) {
                    $collection->joinAttribute(
                        sprintf('%s_%s', $attribute, $locale->getLocaleCode()),
                        sprintf('%s/%s', $collection->getEntity()->getType(), $attribute),
                        'entity_id',
                        null,
                        'left',
                        $locale->getScopeId()
                    );
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

    /**
     * Retrieves translatable attributes
     *
     * @return string[]
     * @throws LocalizedException
     */
    private function getAttributes(): array
    {
        $attributes = [];

        $fields = $this->entityPool->getByType($this->resourceType)->getFields();
        foreach ($fields as $field) {
            if ($field->isTranslatable()) {
                $attributes[] = $field->getCode();
            }
        }

        return array_unique($attributes);
    }
}
