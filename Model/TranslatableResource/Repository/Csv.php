<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Csv\Model;
use Aheadworks\Langshop\Model\Locale\LocaleCodeConverter;
use Aheadworks\Langshop\Model\ResourceModel\Collection\ProcessorInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Translation as TranslationValidation;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection as CsvCollection;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\CollectionFactory;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Translation\Model\ResourceModel\StringUtilsFactory as ResourceModelFactory;
use Magento\Translation\Model\ResourceModel\StringUtils;

class Csv implements RepositoryInterface
{
    private const RESOURCE_TYPE = 'csv';

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
     * @var LocaleCodeConverter
     */
    private LocaleCodeConverter $localeCodeConverter;

    /**
     * @param EntityAttributeProvider $attributeProvider
     * @param ProcessorInterface $collectionProcessor
     * @param CollectionFactory $collectionFactory
     * @param ResourceModelFactory $resourceModelFactory
     * @param TranslationValidation $translationValidation
     * @param EventManagerInterface $eventManager
     * @param LocaleCodeConverter $localeCodeConverter
     */
    public function __construct(
        EntityAttributeProvider $attributeProvider,
        ProcessorInterface $collectionProcessor,
        CollectionFactory $collectionFactory,
        ResourceModelFactory $resourceModelFactory,
        TranslationValidation $translationValidation,
        EventManagerInterface $eventManager,
        LocaleCodeConverter $localeCodeConverter
    ) {
        $this->attributeProvider = $attributeProvider;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->translationValidation = $translationValidation;
        $this->eventManager = $eventManager;
        $this->localeCodeConverter = $localeCodeConverter;
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
    public function save(string $entityId, array $translations): void
    {
        /** @var StringUtils $resourceModel */
        $resourceModel = $this->resourceModelFactory->create();
        $translationByLocales = [];

        foreach ($translations as $translation) {
            $this->translationValidation->validate($translation, self::RESOURCE_TYPE);
            $translationByLocales[$translation->getLocale()] = $translation->getValue();
        }

        foreach ($translationByLocales as $locale => $values) {
            $locale = $this->localeCodeConverter->toMagento($locale);
            foreach ($values as $original => $translation) {
                if ($translation) {
                    $resourceModel->saveTranslate(
                        $original,
                        $translation,
                        $locale,
                        0
                    );
                } else {
                    $resourceModel->deleteTranslate($original, $locale);
                }
            }
        }

        $this->eventManager->dispatch('aw_ls_save_translatable_resource', [
            'resource_type' => self::RESOURCE_TYPE,
            'resource_id' => $entityId,
            'store_id' => 0
        ]);
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
