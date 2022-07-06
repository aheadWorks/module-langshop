<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Test\Unit\Model\ResourceModel\TranslatableResource\Csv;

use Aheadworks\Langshop\Model\Config\Locale as LocaleConfig;
use Aheadworks\Langshop\Model\Csv\File\Reader as CsvReader;
use Aheadworks\Langshop\Model\Csv\Model;
use Aheadworks\Langshop\Model\Csv\ModelFactory;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection\SortingApplier;
use Aheadworks\Langshop\Model\Source\CsvFile;
use Aheadworks\Langshop\Model\TranslatableResource\Csv\Filter\Resolver;
use Aheadworks\Langshop\Model\Translation;
use Aheadworks\Langshop\Model\TranslationFactory;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Module\ModuleListInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CollectionTest extends TestCase
{
    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @var EntityFactoryInterface|MockObject
     */
    private $entityFactory;

    /**
     * @var LocaleConfig|MockObject
     */
    private $localeConfigMock;

    /**
     * @var CsvReader|MockObject
     */
    private $csvReaderMock;

    /**
     * @var ModuleListInterface|MockObject
     */
    private $moduleListMock;

    /**
     * @var Resolver|MockObject
     */
    private $filterResolverMock;

    /**
     * @var MockObject
     */
    private MockObject $translationFactoryMock;

    /**
     * @var SortingApplier|MockObject
     */
    private $sortingApplierMock;

    /**
     * @var MockObject|LoggerInterface
     */
    private $loggerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->entityFactory = $this->createMock(EntityFactoryInterface::class);
        $this->localeConfigMock = $this->createMock(LocaleConfig::class);
        $this->csvReaderMock = $this->createMock(CsvReader::class);
        $this->moduleListMock = $this->createMock(ModuleListInterface::class);
        $this->filterResolverMock = $this->createMock(Resolver::class);
        $this->translationFactoryMock = $this->createMock(TranslationFactory::class);
        $this->sortingApplierMock = $this->createMock(SortingApplier::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->collection = new Collection(
            $this->entityFactory,
            $this->localeConfigMock,
            $this->csvReaderMock,
            $this->moduleListMock,
            $this->translationFactoryMock,
            $this->sortingApplierMock,
            $this->loggerMock,
            $this->filterResolverMock
        );
    }

    /**
     * Test 'loadData' method
     *
     * @return void
     * @throws \Exception
     */
    public function testLoadData(): void
    {
        $translation = $this->createMock(Translation::class);
        $model = $this->createMock(Model::class);
        $collection = $this->createMock(Collection::class);
        $items = [$model];
        $sortOrders = [];
        $filters = [];
        $locale = 'en_US';
        $data = [];
        $moduleNames = ['Aheadworks_Lanshop'];
        $isFilterResolved = true;
        $csvData = [[CsvFile::ORIGINAL_INDEX => 'original', CsvFile::TRANSLATION_INDEX => 'translation']];
        $translationData = ['original'=> 'translation'];
        $storeId = 0;

        $this->translationFactoryMock
            ->expects($this->any())
            ->method('create')
            ->willReturn($translation);

        $this->localeConfigMock
            ->expects($this->any())
            ->method('getValue')
            ->with($storeId)
            ->willReturn($locale);
        $translation
            ->expects($this->any())
            ->method('setLocale')
            ->with($locale)
            ->willReturnSelf();
        $translation
            ->expects($this->any())
            ->method('loadData')
            ->with(null, true)
            ->willReturnSelf();
        $translation
            ->expects($this->any())
            ->method('getData')
            ->willReturn($data);
        $this->moduleListMock
            ->expects($this->any())
            ->method('getNames')
            ->willReturn($moduleNames);
        foreach ($moduleNames as $packageName) {
            $this->entityFactory
                ->expects($this->any())
                ->method('create')
                ->with(Model::class)
                ->willReturn($model);
            $names = explode('_', $packageName);
            $model
                ->expects($this->any())
                ->method('setId')
                ->with($packageName)
                ->willReturnSelf();
            $model
                ->expects($this->any())
                ->method('setVendorName')
                ->with($names[0])
                ->willReturnSelf();
            $model
                ->expects($this->any())
                ->method('setModuleName')
                ->with($names[1])
                ->willReturnSelf();

            $this->filterResolverMock
                ->expects($this->any())
                ->method('resolve')
                ->with($filters, $model)
                ->willReturn($isFilterResolved);

            $this->csvReaderMock
                ->expects($this->any())
                ->method('getCsvData')
                ->with($packageName, Collection::BASE_LOCALE)
                ->willReturn($csvData);
            $originalLines = $this->getOriginalLines($csvData);
            $lines = $this->getTranslationLines($originalLines, $translationData, Collection::BASE_LOCALE);
            $model
                ->expects($this->any())
                ->method('setLines')
                ->with($lines)
                ->willReturnSelf();
            $collection
                ->expects($this->any())
                ->method('addItem')
                ->with($model)
                ->willReturnSelf();
        }

        $this->sortingApplierMock
            ->expects($this->any())
            ->method('apply')
            ->with($items, $sortOrders);

        $this->collection
            ->setIsNeedToAddLinesAttribute(true)
            ->loadData();
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
            $result[$line] = $localeCode === Collection::BASE_LOCALE ? $line : '';

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
            $result[] = $data[CsvFile::ORIGINAL_INDEX];
        }

        return $result;
    }
}
