<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Test\Unit\Model\Service;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FilterInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\Data\Processor\Pool as DataProcessorPool;
use Aheadworks\Langshop\Model\Service\TranslatableResource as TranslatableResourceService;
use Aheadworks\Langshop\Model\TranslatableResource\Converter;
use Aheadworks\Langshop\Model\TranslatableResource\Repository\Pool as RepositoryPool;
use Aheadworks\Langshop\Model\TranslatableResource\Repository\RepositoryInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject;
use Magento\Framework\Webapi\Exception as WebapiException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TranslatableResourceTest extends TestCase
{
    /**
     * @var TranslatableResourceService
     */
    private TranslatableResourceService $translatableResourceService;

    /**
     * @var Converter|MockObject
     */
    private $converterMock;

    /**
     * @var RepositoryPool|MockObject
     */
    private $repositoryPoolMock;

    /**
     * @var SearchCriteriaBuilder|MockObject
     */
    private $searchCriteriaBuilderMock;

    /**
     * @var DataProcessorPool|MockObject
     */
    private $dataProcessorPoolMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->converterMock = $this->createMock(Converter::class);
        $this->repositoryPoolMock = $this->createMock(RepositoryPool::class);
        $this->searchCriteriaBuilderMock = $this->createMock(SearchCriteriaBuilder::class);
        $this->dataProcessorPoolMock = $this->createMock(DataProcessorPool::class);

        $this->translatableResourceService = new TranslatableResourceService(
            $this->converterMock,
            $this->repositoryPoolMock,
            $this->searchCriteriaBuilderMock,
            $this->dataProcessorPoolMock
        );
    }

    /**
     * Test 'getList' method
     *
     * @return void
     * @throws WebapiException
     */
    public function testGetList(): void
    {
        $resourceType = 'resourceType';
        $locale = ['en_US'];
        $page = 1;
        $pageSize = 20;
        $sortBy = null;
        $filter = [$this->createMock(FilterInterface::class)];
        $processedParams = $params = [
            'resourceType' => $resourceType,
            'locale' => $locale,
            'page' => $page,
            'pageSize' => $pageSize,
            'sortBy' => $sortBy,
            'filter' => $filter
        ];

        $repositoryMock = $this->createMock(RepositoryInterface::class);
        $collectionMock = $this->createMock(Collection::class);
        $searchCriteriaMock = $this->createMock(SearchCriteria::class);
        $result = $resourceListMock = $this->createMock(ResourceListInterface::class);
        $dataProcessorMock = $this->createMock(ProcessorInterface::class);

        $processedParams['filter'] = [$this->createMock(Filter::class)];
        $processedParams['sortBy'] = [];

        $this->repositoryPoolMock
            ->expects($this->once())
            ->method('get')
            ->with($resourceType)
            ->willReturn($repositoryMock);
        $dataProcessorMock
            ->expects($this->once())
            ->method('process')
            ->with($params)
            ->willReturn($processedParams);
        $this->dataProcessorPoolMock
            ->expects($this->once())
            ->method('get')
            ->with($resourceType)
            ->willReturn($dataProcessorMock);
        $this->searchCriteriaBuilderMock
            ->expects($this->once())
            ->method('setCurrentPage')
            ->with($processedParams['page'])
            ->willReturn($this->searchCriteriaBuilderMock);
        $this->searchCriteriaBuilderMock
            ->expects($this->once())
            ->method('setPageSize')
            ->with($processedParams['pageSize'])
            ->willReturn($this->searchCriteriaBuilderMock);
        $this->searchCriteriaBuilderMock
            ->expects($this->once())
            ->method('setSortOrders')
            ->with($processedParams['sortBy'])
            ->willReturn($this->searchCriteriaBuilderMock);

        foreach ($processedParams['filter'] as $filterMock) {
            $this->searchCriteriaBuilderMock
                ->expects($this->any())
                ->method('addFilters')
                ->with([$filterMock]);
        }

        $this->searchCriteriaBuilderMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteriaMock);
        $repositoryMock
            ->expects($this->once())
            ->method('getList')
            ->with($searchCriteriaMock, $processedParams['locale'])
            ->willReturn($collectionMock);

        $this->converterMock
            ->expects($this->once())
            ->method('convertCollection')
            ->with($collectionMock, $resourceType)
            ->willReturn($resourceListMock);

        $this->assertSame(
            $result,
            $this->translatableResourceService->getList($resourceType, $locale, $page, $pageSize, $sortBy, $filter)
        );
    }

    /**
     * Test 'getById' method
     *
     * @return void
     * @throws WebapiException
     */
    public function testGetById(): void
    {
        $resourceType = 'resourceType';
        $resourceId = 'resourceId';
        $locale = [];
        $repository = $this->createMock(RepositoryInterface::class);
        $result = $resource = $this->createMock(TranslatableResourceInterface::class);
        $item = $this->createMock(DataObject::class);
        $dataProcessorMock = $this->createMock(ProcessorInterface::class);

        $params = [
            'resourceType' => $resourceType,
            'locale' => $locale
        ];

        $this->repositoryPoolMock
            ->expects($this->once())
            ->method('get')
            ->with($resourceType)
            ->willReturn($repository);
        $dataProcessorMock
            ->expects($this->once())
            ->method('process')
            ->with($params)
            ->willReturn($params);
        $this->dataProcessorPoolMock
            ->expects($this->once())
            ->method('get')
            ->with($resourceType)
            ->willReturn($dataProcessorMock);
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($resourceId, $params['locale'])
            ->willReturn($item);
        $this->converterMock
            ->expects($this->once())
            ->method('convert')
            ->with($item, $resourceType)
            ->willReturn($resource);

        $this->assertSame($result, $this->translatableResourceService->getById($resourceType, $resourceId, $locale));
    }

    /**
     * Test 'getById' method
     *
     * @return void
     * @throws WebapiException
     */
    public function testSave(): void
    {
        $repository = $this->createMock(RepositoryInterface::class);
        $result = $resource = $this->createMock(TranslatableResourceInterface::class);
        $item = $this->createMock(DataObject::class);
        $translation = [$this->createMock(TranslationInterface::class)];
        $dataProcessorMock = $this->createMock(ProcessorInterface::class);
        $resourceType = 'resourceType';
        $resourceId = 'resourceId';

        $this->repositoryPoolMock
            ->expects($this->any())
            ->method('get')
            ->with($resourceType)
            ->willReturn($repository);

        $repository
            ->expects($this->once())
            ->method('save')
            ->with($resourceId, $translation);

        $params = [
            'resourceType' => $resourceType,
            'locale' => []
        ];

        $this->repositoryPoolMock
            ->expects($this->any())
            ->method('get')
            ->with($resourceType)
            ->willReturn($repository);

        $dataProcessorMock
            ->expects($this->once())
            ->method('process')
            ->with($params)
            ->willReturn($params);
        $this->dataProcessorPoolMock
            ->expects($this->once())
            ->method('get')
            ->with($resourceType)
            ->willReturn($dataProcessorMock);

        $repository
            ->expects($this->once())
            ->method('get')
            ->with($resourceId, $params['locale'])
            ->willReturn($item);

        $this->converterMock
            ->expects($this->once())
            ->method('convert')
            ->with($item, $resourceType)
            ->willReturn($resource);

        $this->assertSame(
            $result,
            $this->translatableResourceService->save($resourceType, $resourceId, $translation)
        );
    }
}
