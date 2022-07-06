<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Test\Unit\Model\Service;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FilterInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\Service\TranslatableResource as TranslatableResourceService;
use Aheadworks\Langshop\Model\TranslatableResource\Converter;
use Aheadworks\Langshop\Model\TranslatableResource\Repository\Pool as RepositoryPool;
use Aheadworks\Langshop\Model\TranslatableResource\Repository\RepositoryInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Exception as WebapiException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class TranslatableResourceTest extends TestCase
{
    /**
     * @var TranslatableResourceService
     */
    private TranslatableResourceService $translatableResourceService;

    /**
     * @var Converter|MockObject
     */
    private MockObject $converterMock;

    /**
     * @var RepositoryPool|MockObject
     */
    private MockObject $repositoryPoolMock;

    /**
     * @var SearchCriteriaBuilder|MockObject
     */
    private MockObject $searchCriteriaBuilderMock;

    /**
     * @var ProcessorInterface|MockObject
     */
    private MockObject $dataProcessorMock;

    /**
     * @var MockObject|LoggerInterface
     */
    private MockObject $loggerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->converterMock = $this->createMock(Converter::class);
        $this->repositoryPoolMock = $this->createMock(RepositoryPool::class);
        $this->searchCriteriaBuilderMock = $this->createMock(SearchCriteriaBuilder::class);
        $this->dataProcessorMock = $this->createMock(ProcessorInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->translatableResourceService = new TranslatableResourceService(
            $this->converterMock,
            $this->repositoryPoolMock,
            $this->searchCriteriaBuilderMock,
            $this->dataProcessorMock,
            $this->loggerMock
        );
    }

    /**
     * Test 'getList' method
     *
     * @param bool $throwException
     *
     * @return void
     * @dataProvider dataProvider
     * @throws WebapiException
     */
    public function testGetList(bool $throwException): void
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

        $exceptionMessage = 'message';
        $localizedException = new LocalizedException(__($exceptionMessage));
        $webapiException = new WebapiException(__($exceptionMessage), 500, 500);

        $repositoryMock = $this->createMock(RepositoryInterface::class);
        $collectionMock = $this->createMock(Collection::class);
        $searchCriteriaMock = $this->createMock(SearchCriteria::class);
        $result = $resourceListMock = $this->createMock(ResourceListInterface::class);

        $processedParams['filter'] = [$this->createMock(Filter::class)];
        $processedParams['sortBy'] = [];

        $this->repositoryPoolMock
            ->expects($this->once())
            ->method('get')
            ->with($resourceType)
            ->willReturn($repositoryMock);
        $this->dataProcessorMock
            ->expects($this->once())
            ->method('process')
            ->with($params)
            ->willReturn($processedParams);
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

        if ($throwException) {
            $this->converterMock
                ->expects($this->once())
                ->method('convertCollection')
                ->with($collectionMock, $resourceType)
                ->willThrowException($localizedException);
            $this->loggerMock
                ->expects($this->once())
                ->method('error')
                ->with($exceptionMessage);
            $this->expectExceptionObject($webapiException);
            $result = $webapiException;
        } else {
            $this->converterMock
                ->expects($this->once())
                ->method('convertCollection')
                ->with($collectionMock, $resourceType)
                ->willReturn($resourceListMock);
        }

        $this->assertSame(
            $result,
            $this->translatableResourceService->getList($resourceType, $locale, $page, $pageSize, $sortBy, $filter)
        );
    }

    /**
     * Test 'getById' method
     *
     * @param bool $throwException
     *
     * @return void
     * @dataProvider dataProvider
     * @throws WebapiException
     */
    public function testGetById(bool $throwException): void
    {
        $resourceType = 'resourceType';
        $resourceId = 'resourceId';
        $locale = [];
        $exceptionMessage = 'message';
        $localizedException = new LocalizedException(__($exceptionMessage));
        $webapiException = new WebapiException(__($exceptionMessage), 500, 500);
        $repository = $this->createMock(RepositoryInterface::class);
        $result = $resource = $this->createMock(TranslatableResourceInterface::class);
        $item = $this->createMock(DataObject::class);
        $params = [
            'resourceType' => $resourceType,
            'locale' => $locale
        ];

        $this->repositoryPoolMock
            ->expects($this->once())
            ->method('get')
            ->with($resourceType)
            ->willReturn($repository);
        $this->dataProcessorMock
            ->expects($this->once())
            ->method('process')
            ->with($params)
            ->willReturn($params);
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($resourceId, $params['locale'])
            ->willReturn($item);
        if ($throwException) {
            $this->converterMock
                ->expects($this->once())
                ->method('convert')
                ->with($item, $resourceType)
                ->willThrowException($localizedException);
            $this->loggerMock
                ->expects($this->once())
                ->method('error')
                ->with($exceptionMessage);
            $this->expectExceptionObject($webapiException);
            $result = $webapiException;
        } else {
            $this->converterMock
                ->expects($this->once())
                ->method('convert')
                ->with($item, $resourceType)
                ->willReturn($resource);
        }

        $this->assertSame($result, $this->translatableResourceService->getById($resourceType, $resourceId, $locale));
    }

    /**
     * Test 'getById' method
     *
     * @param bool $throwException
     *
     * @return void
     * @dataProvider dataProvider
     * @throws WebapiException
     */
    public function testSave(bool $throwException): void
    {
        $repository = $this->createMock(RepositoryInterface::class);
        $result = $resource = $this->createMock(TranslatableResourceInterface::class);
        $item = $this->createMock(DataObject::class);
        $translation = ['locale'=> 'en-US', 'key' => 'key', 'value' => 'value'];
        $resourceType = 'resourceType';
        $resourceId = 'resourceId';
        $exceptionMessage = 'message';
        $localizedException = new LocalizedException(__($exceptionMessage));
        $webapiException = new WebapiException(__($exceptionMessage), 500, 500);

        $this->repositoryPoolMock
            ->expects($this->any())
            ->method('get')
            ->with($resourceType)
            ->willReturn($repository);
        if ($throwException) {
            $repository
                ->expects($this->once())
                ->method('save')
                ->with($resourceId, $translation)
                ->willThrowException($localizedException);
            $this->loggerMock
                ->expects($this->once())
                ->method('error')
                ->with($exceptionMessage);
            $this->expectExceptionObject($webapiException);
            $result = $webapiException;
        } else {
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
            $this->dataProcessorMock
                ->expects($this->once())
                ->method('process')
                ->with($params)
                ->willReturn($params);
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
        }

        $this->assertSame(
            $result,
            $this->translatableResourceService->save($resourceType, $resourceId, $translation)
        );
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function dataProvider(): array
    {
        return [[true], [false]];
    }
}
