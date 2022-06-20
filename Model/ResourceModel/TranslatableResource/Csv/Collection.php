<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv;

use Aheadworks\Langshop\Model\Config\Locale as LocaleConfig;
use Aheadworks\Langshop\Model\Csv\Model;
use Aheadworks\Langshop\Model\Csv\ModelFactory;
use Aheadworks\Langshop\Model\Source\CsvFile;
use Aheadworks\Langshop\Model\TranslatableResource\Csv\Filter\Resolver;
use Magento\Framework\File\Csv;
use Magento\Framework\Data\Collection as DataCollection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Module\Dir\Reader as DirReader;
use Magento\Framework\Module\ModuleListInterface;

class Collection extends DataCollection
{
    /**
     * @var Csv
     */
    private Csv $csvFile;

    /**
     * @var DirReader
     */
    private DirReader $dirReader;

    /**
     * @var ModuleListInterface
     */
    private ModuleListInterface $moduleList;

    /**
     * @var LocaleConfig
     */
    private LocaleConfig $localeConfig;

    /**
     * @var ModelFactory
     */
    private ModelFactory $modelFactory;

    /**
     * @var Resolver
     */
    private Resolver $filterResolver;

    /**
     * @var string
     */
    private string $fileName;

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
     * @param EntityFactoryInterface $entityFactory
     * @param Csv $csvFile
     * @param DirReader $dirReader
     * @param ModuleListInterface $moduleList
     * @param LocaleConfig $localeConfig
     * @param ModelFactory $modelFactory
     * @param Resolver $filterResolver
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        Csv $csvFile,
        DirReader $dirReader,
        ModuleListInterface $moduleList,
        LocaleConfig $localeConfig,
        ModelFactory $modelFactory,
        Resolver $filterResolver
    ) {
        parent::__construct($entityFactory);
        $this->csvFile = $csvFile;
        $this->dirReader = $dirReader;
        $this->moduleList = $moduleList;
        $this->localeConfig = $localeConfig;
        $this->modelFactory = $modelFactory;
        $this->filterResolver = $filterResolver;
        $this->updateFilename();
    }

    /**
     * @inheritDoc
     * @return Model[]
     * @throws \Exception
     */
    public function getItems()
    {
        $this->load();
        return $this->_items;
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
            $model = $this->modelFactory->create();
            $names = explode('_', $packageName);
            $model
                ->setId($packageName)
                ->setVendorName($names[0])
                ->setModuleName($names[1]);
            if ($this->filterResolver->resolve($this->_filters, $model)) {
                try {
                    $dir = $this->dirReader->getModuleDir('i18n', $packageName) . '/' . $this->fileName;
                    $lines = $this->csvFile->getData($dir);
                    $model->setLines($this->prepareLines($lines));
                } catch (\Exception $e) {
                    $model->setLines([]);
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
     * @inheritDoc
     * @return Model
     * @throws \Exception
     */
    public function getItemById($id)
    {
        $this->load();
        return $this->_items[$id] ?? null;
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
        foreach ($lines as $index => $value) {
            $result[$index] = $value[CsvFile::TRANSLATION_INDEX];
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

        return $this->updateFilename();
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
     * Update filename for current store id
     *
     * @return Collection
     */
    private function updateFilename(): Collection
    {
        $this->fileName = $this->localeConfig->getValue($this->getStoreId()) . '.csv';

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
