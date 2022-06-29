<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Test\Unit\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Csv\Model;
use Aheadworks\Langshop\Model\Locale\LocaleCodeConverter;
use Aheadworks\Langshop\Model\ResourceModel\Collection\ProcessorInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Translation as TranslationValidation;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection as CsvCollection;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\CollectionFactory;
use Aheadworks\Langshop\Model\TranslatableResource\Repository\Csv as CsvRepository;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Translation\Model\ResourceModel\StringUtilsFactory as ResourceModelFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CsvTest extends TestCase
{
    /**
     * @var CsvRepository
     */
    private CsvRepository $csvRepository;

    /**
     * @var CollectionFactory|MockObject
     */
    private $collectionFactoryMock;

    /**
     * @var EntityAttributeProvider|MockObject
     */
    private $attributeProviderMock;

    /**
     * @var ProcessorInterface|MockObject
     */
    private $collectionProcessorMock;

    /**
     * @var ResourceModelFactory|MockObject
     */
    private $resourceModelFactoryMock;

    /**
     * @var TranslationValidation|MockObject
     */
    private $translationValidationMock;

    /**
     * @var EventManagerInterface|MockObject
     */
    private $eventManagerMock;

    /**
     * @var LocaleCodeConverter|MockObject
     */
    private $localeCodeConverterMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {

        $this->attributeProviderMock = $this->createMock(EntityAttributeProvider::class);
        $this->collectionProcessorMock = $this->createMock(ProcessorInterface::class);
        $this->collectionFactoryMock = $this->createMock(CollectionFactory::class);
        $this->resourceModelFactoryMock = $this->createMock(ResourceModelFactory::class);
        $this->translationValidationMock = $this->createMock(TranslationValidation::class);
        $this->eventManagerMock = $this->createMock(EventManagerInterface::class);
        $this->localeCodeConverterMock = $this->createMock(LocaleCodeConverter::class);

        $this->csvRepository = new CsvRepository(
            $this->attributeProviderMock,
            $this->collectionProcessorMock,
            $this->collectionFactoryMock,
            $this->resourceModelFactoryMock,
            $this->translationValidationMock,
            $this->eventManagerMock,
            $this->localeCodeConverterMock
        );
    }

    /**
     * Test 'getList' method
     *
     * @return void
     * @throws LocalizedException
     */
    public function testGetList(): void
    {
        $collection = $this->createMock(CsvCollection::class);
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $localeScopes = [$this->createMock(RecordInterface::class)];

        $this->collectionFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($collection);
        $this->collectionProcessorMock
            ->expects($this->once())
            ->method('process')
            ->with($searchCriteria, $collection);

        $collection = $this->addLocalizedAttributes($collection, $localeScopes);
        $this->assertSame($collection, $this->csvRepository->getList($searchCriteria, $localeScopes));
    }

    /**
     * Test 'get' method
     *
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function testGet(): void
    {
        $collection = $this->createMock(CsvCollection::class);
        $model = $this->createMock(Model::class);
        $localeScopes = [$this->createMock(RecordInterface::class)];
        $entityId = 'VendorName_ModuleName';

        $this->collectionFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($collection);
        $collection
            ->expects($this->once())
            ->method('addEntityIdFilter')
            ->with($entityId);

        $collection = $this->addLocalizedAttributes($collection, $localeScopes);
        $collection
            ->expects($this->once())
            ->method('getFirstItem')
            ->willReturn($model);

        $this->assertSame($model, $this->csvRepository->get($entityId, $localeScopes));
    }

    /**
     * Test 'addLocalizedAttributes' method
     *
     * @param MockObject $collection
     * @param MockObject[] $localeScopes
     * @return MockObject
     */
    private function addLocalizedAttributes(MockObject $collection, array $localeScopes): MockObject
    {
        $localizedCollection = $collection;
        $translatableAttributeCodes = ['code'];

        $item = $this->createMock(Model::class);
        $localizedItems = [$this->createMock(Model::class)];
        $value = [];
        $code = 'code';
        $localizedValue = [];
        $collectionSize = 1;

        $this->attributeProviderMock
            ->expects($this->once())
            ->method('getCodesOfTranslatableFields')
            ->with('csv')
            ->willReturn($translatableAttributeCodes);
        $localizedCollection
            ->expects($this->once())
            ->method('setIsNeedToAddLinesAttribute');

        foreach ($localeScopes as $localeScope) {
            $scopeId = 0;
            $localeScope
                ->expects($this->any())
                ->method('getScopeId')
                ->willReturn($scopeId);
            $localizedCollection
                ->expects($this->any())
                ->method('setStoreId')
                ->with($scopeId)
                ->willReturn($localizedCollection);

            $localizedCollection
                ->expects($this->any())
                ->method('clear');
            $localizedCollection
                ->expects($this->any())
                ->method('getItems')
                ->willReturn($localizedItems);

            foreach ($localizedItems as $localizedItem) {
                $id = 'VendorName_ModuleName';
                $localizedItem
                    ->expects($this->any())
                    ->method('getId')
                    ->willReturn($id);
                $collection
                    ->expects($this->any())
                    ->method('getItemById')
                    ->with($id)
                    ->willReturn($item);
                foreach ($translatableAttributeCodes as $attributeCode) {
                    $item
                        ->expects($this->any())
                        ->method('getData')
                        ->with($attributeCode)
                        ->willReturn($value);
                    $localeScope
                        ->expects($this->any())
                        ->method('getLocaleCode')
                        ->willReturn($code);
                    $localizedItem
                        ->expects($this->any())
                        ->method('getData')
                        ->with($attributeCode)
                        ->willReturn($localizedValue);
                    $value[$code] = $localizedValue;
                    $item
                        ->expects($this->any())
                        ->method('setData')
                        ->with($attributeCode, $value);
                }
            }
        }
        $localizedCollection
            ->expects($this->once())
            ->method('getSize')
            ->willReturn($collectionSize);
        $collection
            ->expects($this->once())
            ->method('setSize')
            ->with($collectionSize);

        return $collection;
    }
}
