<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv;

use Aheadworks\Langshop\Model\Csv\File\Reader as CsvReader;
use Aheadworks\Langshop\Model\Csv\Model;
use Aheadworks\Langshop\Model\Csv\ModelFactory;
use Aheadworks\Langshop\Model\Locale\LocaleCodeConverter;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionInterface;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionTrait;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection\SortingApplier;
use Aheadworks\Langshop\Model\Source\CsvFile;
use Aheadworks\Langshop\Model\TranslatableResource\Csv\Filter\Resolver;
use Aheadworks\Langshop\Model\TranslationFactory;
use Exception;
use Magento\Framework\Data\Collection as DataCollection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Store\Model\Store;
use Psr\Log\LoggerInterface;

class Collection extends DataCollection implements CollectionInterface
{
    use CollectionTrait;

    /**
     * @var LocaleScopeRepository
     */
    private LocaleScopeRepository $localeScopeRepository;

    /**
     * @var LocaleCodeConverter
     */
    private LocaleCodeConverter $localeCodeConverter;

    /**
     * @var CsvReader
     */
    private CsvReader $csvReader;

    /**
     * @var ModuleListInterface
     */
    private ModuleListInterface $moduleList;

    /**
     * @var Resolver
     */
    private Resolver $filterResolver;

    /**
     * @var TranslationFactory
     */
    private TranslationFactory $translationFactory;

    /**
     * @var SortingApplier
     */
    private SortingApplier $sortingApplier;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var int
     */
    protected $_pageSize = 20;

    /**
     * @var Model[]
     */
    protected $_items = [];

    /**
     * @var string
     */
    protected $_itemObjectClass = Model::class;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LocaleScopeRepository $localeScopeRepository
     * @param LocaleCodeConverter $localeCodeConverter
     * @param CsvReader $csvReader
     * @param ModuleListInterface $moduleList
     * @param TranslationFactory $translationFactory
     * @param SortingApplier $sortingApplier
     * @param LoggerInterface $logger
     * @param Resolver $filterResolver
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LocaleScopeRepository $localeScopeRepository,
        LocaleCodeConverter $localeCodeConverter,
        CsvReader $csvReader,
        ModuleListInterface $moduleList,
        TranslationFactory $translationFactory,
        SortingApplier $sortingApplier,
        LoggerInterface $logger,
        Resolver $filterResolver
    ) {
        parent::__construct($entityFactory);

        $this->localeScopeRepository = $localeScopeRepository;
        $this->localeCodeConverter = $localeCodeConverter;
        $this->csvReader = $csvReader;
        $this->moduleList = $moduleList;
        $this->translationFactory = $translationFactory;
        $this->sortingApplier = $sortingApplier;
        $this->logger = $logger;
        $this->filterResolver = $filterResolver;
    }

    /**
     * Load data
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return DataCollection
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        return parent::load($printQuery, $logQuery);
    }

    /**
     * Load data
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return DataCollection
     * @throws Exception
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        $translation = $this->translationFactory->create();
        $localeCode = $this->getLocaleCode($this->getStoreId());
        $translationData = $translation->setLocale($localeCode)->loadData(null, true)->getData();

        foreach ($this->moduleList->getNames() as $packageName) {
            /** @var Model $model */
            $model = $this->_entityFactory->create($this->_itemObjectClass);
            $names = explode('_', $packageName);
            $model
                ->setId($packageName)
                ->setVendorName($names[0])
                ->setModuleName($names[1]);
            try {
                $data = $this->csvReader->getCsvData($packageName, $this->getLocaleCode());
                $lines = $this->getTranslationLines(
                    $this->getOriginalLines($data),
                    $translationData,
                    $localeCode
                );
                $model->setLines($lines);
            } catch (Exception $e) {
                $this->logger->error($e->getMessage());
                $model->setLines([]);
            }

            if ($this->isNeedToAddItemToResult($model)) {
                $this->addItem($model);
            }
        }

        $this->_totalRecords = count($this->_items);
        $this->sortingApplier->apply($this->_items, $this->_orders);
        $this->_setIsLoaded();
        $this->applyPagination();

        return $this;
    }

    /**
     * Get translation lines
     *
     * @param string[] $lines
     * @param array $translationData
     * @param string $localeCode
     * @return string[]
     * @throws NoSuchEntityException
     */
    private function getTranslationLines(array $lines, array $translationData, string $localeCode): array
    {
        $result = [];
        foreach ($lines as $line) {
            $result[$line] = $localeCode === $this->getLocaleCode() ? $line : '';

            foreach ($translationData as $translationValue) {
                if (isset($translationValue[$line])) {
                    $result[$line] = $translationValue[$line];
                }
            }
        }

        return $result;
    }

    /**
     * Get original lines
     *
     * @param array $csvData
     * @return array
     */
    private function getOriginalLines(array $csvData): array
    {
        $result = [];
        foreach ($csvData as $data) {
            $originalString = $data[CsvFile::ORIGINAL_INDEX];
            if ($originalString && strlen($originalString) < 256) {
                $result[] = $originalString;
            }
        }

        return $result;
    }

    /**
     * Add field filter to collection
     *
     * @param array $fields
     * @param array|int|string $condition
     * @return Collection|void
     */
    public function addFieldToFilter($fields, $condition)
    {
        foreach ($fields as $index => $field) {
            $this->addFilter($field, $condition[$index]);
        }
    }

    /**
     * Set collection all items count
     *
     * @param int $size
     * @return Collection
     */
    public function setSize(int $size): Collection
    {
        $this->_totalRecords = $size;
        $this->_setIsLoaded();
        return $this;
    }

    /**
     * Add entity id filter
     *
     * @param string $id
     * @return Collection
     */
    public function addEntityIdFilter(string $id): Collection
    {
        return $this->addFilter(Model::ID, $id);
    }

    /**
     * Add order
     *
     * @param string $field
     * @param string $direction
     * @return $this
     */
    public function addOrder(string $field, string $direction = 'ASC'): Collection
    {
        $this->_orders[$field] = $direction;
        return $this;
    }

    /**
     * Apply pagination
     *
     * @return Collection
     */
    private function applyPagination(): Collection
    {
        $offset = $this->getPageSize() * ($this->getCurPage() - 1);
        $this->_items = array_slice($this->_items, $offset, $this->getPageSize());
        return $this;
    }

    /**
     * Retrieves locale scope by store id
     *
     * @param int $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    private function getLocaleCode(int $storeId = Store::DEFAULT_STORE_ID): string
    {
        $localeScopes = array_merge(
            $this->localeScopeRepository->getList(),
            [$this->localeScopeRepository->getPrimary($this->getResourceType())],
        );

        foreach ($localeScopes as $localeScope) {
            if ($localeScope->getScopeId() === $storeId) {
                return $this->localeCodeConverter->toMagento(
                    $localeScope->getLocaleCode()
                );
            }
        }

        throw new NoSuchEntityException(
            __('Store with identifier = "%1" is not available for translate.', $storeId)
        );
    }

    /**
     * Check if the given item matches applied filters and contains translatable data
     *
     * @param Model $item
     * @return bool
     */
    private function isNeedToAddItemToResult(Model $item): bool
    {
        return $this->filterResolver->resolve($this->_filters, $item)
            && (count($item->getLines()) > 0);
    }
}
