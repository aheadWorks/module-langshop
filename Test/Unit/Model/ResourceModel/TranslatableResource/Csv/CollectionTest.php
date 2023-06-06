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
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection\Item\Hydrator as CsvCollectionItemHydrator;
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
     * @var CsvCollectionItemHydrator|MockObject
     */
    private $csvCollectionItemHydratorMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->entityFactory = $this->createMock(EntityFactoryInterface::class);
        $this->localeScopeRepositoryMock = $this->createMock(LocaleScopeRepository::class);
        $this->localeCodeConverterMock = $this->createMock(LocaleCodeConverter::class);
        $this->moduleListMock = $this->createMock(ModuleListInterface::class);
        $this->filterResolverMock = $this->createMock(Resolver::class);
        $this->translationFactoryMock = $this->createMock(TranslationFactory::class);
        $this->sortingApplierMock = $this->createMock(SortingApplier::class);
        $this->csvCollectionItemHydratorMock = $this->createMock(CsvCollectionItemHydrator::class);

        $this->collection = new Collection(
            $this->entityFactory,
            $this->localeScopeRepositoryMock,
            $this->localeCodeConverterMock,
            $this->moduleListMock,
            $this->translationFactoryMock,
            $this->sortingApplierMock,
            $this->filterResolverMock,
            $this->csvCollectionItemHydratorMock
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

            $this->csvCollectionItemHydratorMock
                ->expects($this->any())
                ->method('fillWithData')
                ->willReturn($model);

            $this->filterResolverMock
                ->expects($this->any())
                ->method('resolve')
                ->with($filters, $model)
                ->willReturn($isFilterResolved);
        }

        $this->collection
            ->loadData();
    }
}
