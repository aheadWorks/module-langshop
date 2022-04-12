<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Model\TranslatableResource\EntityAttribute;
use Aheadworks\Langshop\Model\TranslatableResource\LocaleScope;
use Aheadworks\Langshop\Model\TranslatableResource\RepositoryInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Translation as TranslationValidation;
use Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection as CatalogAbstractCollection;
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
     * @var LocaleScope
     */
    private LocaleScope $localeScope;

    /**
     * @var EntityAttribute
     */
    private EntityAttribute $entityAttribute;

    /**
     * @var TranslationValidation
     */
    private TranslationValidation $translationValidation;

    /**
     * @var AbstractCollectionFactory
     */
    private AbstractCollectionFactory $collectionFactory;

    /**
     * @var string
     */
    private string $resourceType;

    /**
     * @param LocaleScope $localeScope
     * @param EntityAttribute $entityAttribute
     * @param TranslationValidation $translationValidation
     * @param AbstractCollectionFactory $collectionFactory
     * @param string $resourceType
     */
    public function __construct(
        LocaleScope $localeScope,
        EntityAttribute $entityAttribute,
        TranslationValidation $translationValidation,
        AbstractCollectionFactory $collectionFactory,
        string $resourceType
    ) {
        $this->localeScope = $localeScope;
        $this->entityAttribute = $entityAttribute;
        $this->translationValidation = $translationValidation;
        $this->collectionFactory = $collectionFactory;
        $this->resourceType = $resourceType;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): AbstractCollection
    {
        $collection = $this->collectionFactory->create();

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
    public function save(int $entityId, array $translations): void
    {
        foreach ($translations as $translation) {
            $this->translationValidation->validate($translation, $this->resourceType);
        }
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

            foreach ($this->localeScope->getList() as $localeScope) {
                $localizedCollection->clear()->setStoreId($localeScope->getScopeId());

                /** @var AbstractModel $localizedItem */
                foreach ($localizedCollection as $localizedItem) {
                    foreach ($attributeCodes[1] as $attributeCode) {
                        $item = $collection->getItemById($localizedItem->getId());

                        $value = $item->getData($attributeCode) ?? [];
                        $value[$localeScope->getLocaleCode()] = $localizedItem->getData($attributeCode);
                        $item->setData($attributeCode, $value);
                    }
                }
            }
        }

        return $collection;
    }
}
