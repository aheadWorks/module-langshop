<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Unit\Model\ResourceModel\TranslatableResource\Csv;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Csv\File\Reader as CsvReader;
use Aheadworks\Langshop\Model\Csv\Model;
use Aheadworks\Langshop\Model\Csv\ModelFactory;
use Aheadworks\Langshop\Model\Locale\LocaleCodeConverter;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
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
     * @var LocaleScopeRepository|MockObject
     */
    private $localeScopeRepositoryMock;

    /**
     * @var LocaleCodeConverter|MockObject
     */
    private $localeCodeConverterMock;

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
     * @var TranslationFactory|MockObject
     */
    private $translationFactoryMock;

    /**
     * @var SortingApplier|MockObject
     */
    private $sortingApplierMock;

    /**
     * @var LoggerInterface|MockObject
     */
    private $loggerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->entityFactory = $this->createMock(EntityFactoryInterface::class);
        $this->localeScopeRepositoryMock = $this->createMock(LocaleScopeRepository::class);
        $this->localeCodeConverterMock = $this->createMock(LocaleCodeConverter::class);
        $this->csvReaderMock = $this->createMock(CsvReader::class);
        $this->moduleListMock = $this->createMock(ModuleListInterface::class);
        $this->filterResolverMock = $this->createMock(Resolver::class);
        $this->translationFactoryMock = $this->createMock(TranslationFactory::class);
        $this->sortingApplierMock = $this->createMock(SortingApplier::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->collection = new Collection(
            $this->entityFactory,
            $this->localeScopeRepositoryMock,
            $this->localeCodeConverterMock,
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
        $localeScope = $this->createMock(RecordInterface::class);
        $model = $this->createMock(Model::class);

        $filters = [];
        $locale = 'en_US';
        $data = [];
        $moduleNames = ['Aheadworks_Lanshop'];
        $isFilterResolved = true;
        $csvData = [[CsvFile::ORIGINAL_INDEX => 'original', CsvFile::TRANSLATION_INDEX => 'translation']];
        $translationData = ['original'=> 'translation'];
        $storeId = 0;

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

        $this->translationFactoryMock
            ->expects($this->any())
            ->method('create')
            ->willReturn($translation);

        $localeScope
            ->expects($this->any())
            ->method('getScopeId')
            ->willReturn($storeId);
        $localeScope
            ->expects($this->any())
            ->method('getLocaleCode')
            ->willReturn($locale);

        $this->localeScopeRepositoryMock
            ->expects($this->any())
            ->method('getList')
            ->willReturn([$localeScope]);
        $this->localeScopeRepositoryMock
            ->expects($this->any())
            ->method('getPrimary')
            ->willReturn($localeScope);

        $this->localeCodeConverterMock
            ->expects($this->any())
            ->method('toMagento')
            ->willReturn($locale);

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
                ->with($packageName, $locale)
                ->willReturn($csvData);
            $originalLines = $this->getOriginalLines($csvData);
            $lines = $this->getTranslationLines($originalLines, $translationData, $locale);
            $model
                ->expects($this->any())
                ->method('setLines')
                ->with($lines)
                ->willReturnSelf();
        }

        $this->collection
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
            $result[$line] = $localeCode === 'en_US' ? $line : '';

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
