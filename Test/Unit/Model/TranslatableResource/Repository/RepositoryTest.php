<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Test\Unit\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute\Collection as AttributeCollection;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Category\Collection as CategoryCollection;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product\Collection as ProductCollection;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Repository\Repository;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Translation as TranslationValidation;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\AbstractDbFactory as CollectionFactory;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDbFactory as ResourceModelFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    /**
     * @var Repository
     */
    private Repository $repository;

    /**
     * @var CollectionFactory|MockObject
     */
    private MockObject $collectionFactoryMock;

    /**
     * @var ResourceModelFactory|MockObject
     */
    private MockObject $resourceModelFactoryMock;

    /**
     * @var TranslationValidation|MockObject
     */
    private MockObject $translationValidationMock;

    /**
     * @var EventManagerInterface|MockObject
     */
    private MockObject $eventManagerMock;

    /**
     * @var LocaleScopeRepository|MockObject
     */
    private MockObject $localeScopeRepositoryMock;

    /**
     * @var EntityAttributeProvider|MockObject
     */
    private MockObject $attributeProviderMock;

    /**
     * @var CollectionProcessorInterface|MockObject
     */
    private MockObject $collectionProcessorMock;

    /**
     * @var string
     */
    private string $resourceType;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->collectionFactoryMock = $this->createMock(CollectionFactory::class);
        $this->resourceModelFactoryMock = $this->createMock(ResourceModelFactory::class);
        $this->translationValidationMock = $this->createMock(TranslationValidation::class);
        $this->eventManagerMock = $this->createMock(EventManagerInterface::class);
        $this->localeScopeRepositoryMock = $this->createMock(LocaleScopeRepository::class);
        $this->attributeProviderMock = $this->createMock(EntityAttributeProvider::class);
        $this->collectionProcessorMock = $this->createMock(CollectionProcessorInterface::class);
        $this->resourceType = 'resourceType';

        $this->repository = new Repository(
            $this->collectionFactoryMock,
            $this->resourceModelFactoryMock,
            $this->translationValidationMock,
            $this->eventManagerMock,
            $this->localeScopeRepositoryMock,
            $this->attributeProviderMock,
            $this->collectionProcessorMock,
            $this->resourceType
        );
    }

    /**
     * Test 'getList' method
     *
     * @param string $collectionType
     *
     * @return void
     * @dataProvider dataProvider
     * @throws LocalizedException
     */
    public function testGetList(string $collectionType): void
    {
        $collection = $this->createMock($collectionType);
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $localeScopes = [$this->createMock(RecordInterface::class)];

        $this->collectionFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($collection);

        $collection
            ->expects($this->once())
            ->method('setResourceType')
            ->with($this->resourceType)
            ->willReturn($collection);
        $this->collectionProcessorMock
            ->expects($this->once())
            ->method('process')
            ->with($searchCriteria, $collection);

        $collection = $this->addLocalizedAttributes($collection, $localeScopes);

        $this->assertSame($collection, $this->repository->getList($searchCriteria, $localeScopes));
    }

    /**
     * Test 'save' method
     *
     * @param string $collectionType
     *
     * @return void
     * @dataProvider dataProvider
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function testGet(string $collectionType): void
    {
        $entityId = '1';
        $localeScopes = [$this->createMock(RecordInterface::class)];
        $item = $this->createMock(AbstractModel::class);

        $collection = $this->prepareCollectionById($entityId, $collectionType);
        $collection = $this->addLocalizedAttributes($collection, $localeScopes);
        $collection
            ->expects($this->once())
            ->method('getFirstItem')
            ->willReturn($item);

        $this->assertSame($item, $this->repository->get($entityId, $localeScopes));
    }

    /**
     * Test 'save' method
     *
     * @param string $collectionType
     *
     * @return void
     * @dataProvider dataProvider
     * @throws LocalizedException
     */
    public function testSave(string $collectionType): void
    {
        $entityId = 'entityId';
        $translations = [
            $this->createMock(TranslationInterface::class)
        ];

        $resourceModel = $this->createMock(ResourceModel::class);
        $item = $this->createMock(AbstractModel::class);
        $translationByLocales = [];
        $locale = 'en-US';
        $localeScopes = [$this->createMock(RecordInterface::class)];
        $scopeId = 0;
        $value = 'value';
        $key = 'key';

        $this->resourceModelFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($resourceModel);

        /** @var MockObject $translation */
        foreach ($translations as $index => $translation) {
            $this->translationValidationMock
                ->expects($this->any())
                ->method('validate')
                ->with($translation, $this->resourceType);

            $translation
                ->expects($this->any())
                ->method('getLocale')
                ->willReturn($locale);
            $translation
                ->expects($this->any())
                ->method('getKey')
                ->willReturn($key);
            $translation
                ->expects($this->any())
                ->method('getValue')
                ->willReturn($value);
            $translationByLocales[$locale][$key] = $value;
        }

         foreach ($translationByLocales as $locale => $values) {
             $collection = $this->prepareCollectionById($entityId, $collectionType);
             $collection
                 ->expects($this->any())
                 ->method('getFirstItem')
                 ->willReturn($item);
             $item
                 ->expects($this->any())
                 ->method('addData')
                 ->with($values)
                 ->willReturnSelf();
             $this->localeScopeRepositoryMock
                 ->expects($this->any())
                 ->method('getByLocale')
                 ->with([$locale])
                 ->willReturn($localeScopes);
             /** @var MockObject $localeScope */
             foreach ($localeScopes as $localeScope) {
                 $localeScope
                     ->expects($this->any())
                     ->method('getScopeId')
                     ->willReturn($scopeId);
                 $item
                     ->expects($this->any())
                     ->method('setData')
                     ->with('store_id', $scopeId)
                     ->willReturnSelf();
                 $resourceModel
                     ->expects($this->any())
                     ->method('save')
                     ->with($item)
                     ->willReturnSelf();
                 $this->eventManagerMock
                     ->expects($this->any())
                     ->method('dispatch')
                     ->with('aw_ls_save_translatable_resource', [
                         'resource_type' => $this->resourceType,
                         'resource_id' => $entityId,
                         'store_id' => $scopeId
                     ]);
             }
         }

         $this->repository->save($entityId, $translations);
    }

    /**
     * Test 'prepareCollectionById' method
     *
     * @param string $entityId
     * @param string $collectionType
     *
     * @return MockObject
     */
    private function prepareCollectionById(string $entityId, string $collectionType): MockObject
    {
        $collection = $this->createMock($collectionType);
        $resource = $this->createMock(ResourceModel::class);
        $fieldName = 'entity_id';

        $this->collectionFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($collection);
        $collection
            ->expects($this->once())
            ->method('setResourceType')
            ->with($this->resourceType)
            ->willReturn($collection);
        $collection
            ->expects($this->once())
            ->method('getResource')
            ->willReturn($resource);
        $resource
            ->expects($this->once())
            ->method('getIdFieldName')
            ->willReturn($fieldName);
        $collection
            ->expects($this->once())
            ->method('addFieldToFilter')
            ->with($fieldName, $entityId)
            ->willReturn($collection);

        $collection
            ->expects($this->once())
            ->method('getSize')
            ->willReturn(20);

        return $collection;
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
        $localizedItems = [$this->createMock(AbstractModel::class)];
        $item = $this->createMock(AbstractModel::class);
        $translatableAttributeCodes = ['translatableCode'];
        $untranslatableAttributeCodes = ['untranslatableCode'];
        $localizedCollection = $collection;
        $itemId = 1;
        $scopeId = 0;
        $value = [];
        $localizedValue = 'value';
        $localeCode = 'en_US';

        $this->attributeProviderMock
            ->expects($this->any())
            ->method('getCodesOfTranslatableFields')
            ->with($this->resourceType)
            ->willReturn($translatableAttributeCodes);
        $this->attributeProviderMock
            ->expects($this->any())
            ->method('getCodesOfUntranslatableFields')
            ->with($this->resourceType)
            ->willReturn($untranslatableAttributeCodes);

        foreach ($localeScopes as $localeScope) {
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
                ->method('clear')
                ->willReturn($localizedCollection);
            $localizedCollection
                ->expects($this->any())
                ->method('getItems')
                ->willReturn($localizedItems);

            /** @var MockObject $localizedItem */
            foreach ($localizedItems as $localizedItem) {
                $localizedItem
                    ->expects($this->any())
                    ->method('getId')
                    ->willReturn($itemId);
                $collection
                    ->expects($this->any())
                    ->method('getItemById')
                    ->with($itemId)
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
                        ->willReturn($localeCode);
                    $localizedItem
                        ->expects($this->any())
                        ->method('getData')
                        ->with($attributeCode)
                        ->willReturn($localizedValue);
                    $value[$localeCode] = $localizedValue;
                    $item
                        ->expects($this->any())
                        ->method('setData')
                        ->with($attributeCode, $value)
                        ->willReturn($item);
                }
            }
        }

        return $collection;
    }

    /**
     * Data provider
     *
     * @return string[][]
     */
    public function dataProvider(): array
    {
        return [
            [ProductCollection::class],
            [CategoryCollection::class],
            [AttributeCollection::class]
        ];
    }
}
