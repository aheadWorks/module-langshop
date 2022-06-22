<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv;

use Aheadworks\Langshop\Model\Csv\File\Reader as CsvReader;
use Aheadworks\Langshop\Model\Csv\Model;
use Aheadworks\Langshop\Model\Csv\ModelFactory;
use Aheadworks\Langshop\Model\Source\CsvFile;
use Aheadworks\Langshop\Model\TranslatableResource\Csv\Filter\Resolver;
use Magento\Framework\Data\Collection as DataCollection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Module\ModuleListInterface;
use Psr\Log\LoggerInterface;

class Collection extends DataCollection
{
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
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var int
     */
    private int $storeId = 0;

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
     * @param CsvReader $csvReader
     * @param ModuleListInterface $moduleList
     * @param LoggerInterface $logger
     * @param Resolver $filterResolver
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        CsvReader $csvReader,
        ModuleListInterface $moduleList,
        LoggerInterface $logger,
        Resolver $filterResolver
    ) {
        parent::__construct($entityFactory);
        $this->csvReader = $csvReader;
        $this->moduleList = $moduleList;
        $this->logger = $logger;
        $this->filterResolver = $filterResolver;
    }

    /**
     * @inheritdoc
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        return parent::load($printQuery, $logQuery);
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
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
                        $lines = $this->csvReader->getCsvData($packageName, $this->getStoreId());
                        $model->setLines($this->prepareLines($lines));
                    } catch (\Exception $e) {
                        $this->logger->error($e->getMessage());
                        $model->setLines([]);
                    }
                }
                $this->addItem($model);
            }
        }

        $this->_totalRecords = count($this->_items);
        $this->applyPagination();
        $this->_setIsLoaded();

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
     * Prepare lines
     *
     * @param string[] $lines
     * @return string[]
     */
    private function prepareLines(array $lines): array
    {
        $result = [];
        foreach ($lines as $value) {
            $result[$value[CsvFile::ORIGINAL_INDEX]] = $value[CsvFile::TRANSLATION_INDEX];
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
     * Set store id
     *
     * @param int $storeId
     * @return Collection
     */
    public function setStoreId(int $storeId): Collection
    {
        $this->storeId = $storeId;

        return $this;
    }

    /**
     * Get store id
     *
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->storeId;
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
