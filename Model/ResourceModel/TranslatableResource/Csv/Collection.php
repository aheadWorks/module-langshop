<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv;

use Aheadworks\Langshop\Model\Config\Locale as LocaleConfig;
use Aheadworks\Langshop\Model\Csv\File\Reader as CsvReader;
use Aheadworks\Langshop\Model\Csv\Model;
use Aheadworks\Langshop\Model\Csv\ModelFactory;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionInterface;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionTrait;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection\SortingApplier;
use Aheadworks\Langshop\Model\Source\CsvFile;
use Aheadworks\Langshop\Model\TranslatableResource\Csv\Filter\Resolver;
use Aheadworks\Langshop\Model\TranslationFactory;
use Exception;
use Magento\Framework\Data\Collection as DataCollection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Module\ModuleListInterface;
use Psr\Log\LoggerInterface;

class Collection extends DataCollection implements CollectionInterface
{
    use CollectionTrait;

    public const BASE_LOCALE = 'en_US';

    /**
     * @var LocaleConfig
     */
    private LocaleConfig $localeConfig;

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
     * @var bool
     */
    private bool $needToAddLines = false;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LocaleConfig $localeConfig
     * @param CsvReader $csvReader
     * @param ModuleListInterface $moduleList
     * @param TranslationFactory $translationFactory
     * @param SortingApplier $sortingApplier
     * @param LoggerInterface $logger
     * @param Resolver $filterResolver
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LocaleConfig $localeConfig,
        CsvReader $csvReader,
        ModuleListInterface $moduleList,
        TranslationFactory $translationFactory,
        SortingApplier $sortingApplier,
        LoggerInterface $logger,
        Resolver $filterResolver
    ) {
        parent::__construct($entityFactory);
        $this->localeConfig = $localeConfig;
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
        $locale = $this->localeConfig->getValue($this->getStoreId());
        $translationData = $translation->setLocale($locale)->loadData(null, true)->getData();

        foreach ($this->moduleList->getNames() as $packageName) {
            $model = $this->_entityFactory->create($this->_itemObjectClass);
            $names = explode('_', $packageName);
            $model
                ->setId($packageName)
                ->setVendorName($names[0])
                ->setModuleName($names[1]);
            if ($this->filterResolver->resolve($this->_filters, $model)) {
                if ($this->needToAddLines) {
                    try {
                        $data = $this->csvReader->getCsvData($packageName, self::BASE_LOCALE);
                        $lines = $this->getTranslationLines($this->getOriginalLines($data), $translationData, $locale);
                        $model->setLines($lines);
                    } catch (Exception $e) {
                        $this->logger->error($e->getMessage());
                        $model->setLines([]);
                    }
                }
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
     * Set is need to add lines to result collection
     *
     * @param bool $isNeed
     * @return $this
     */
    public function setIsNeedToAddLinesAttribute(bool $isNeed): Collection
    {
        $this->needToAddLines = $isNeed;
        return $this;
    }

    /**
     * Get translation lines
     *
     * @param string[] $lines
     * @param array $translationData
     * @param string $localeCode
     * @return string[]
     */
    private function getTranslationLines(array $lines, array $translationData, string $localeCode): array
    {
        $result = [];
        foreach ($lines as $line) {
            $result[$line] = $localeCode === self::BASE_LOCALE ? $line : '';

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
            if (count_chars($originalString) < 256) {
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
}
