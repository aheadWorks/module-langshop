<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Unit\Model\Service;

use Aheadworks\Langshop\Api\Data\StatusInterface;
use Aheadworks\Langshop\Model\ResourceModel\Status as StatusResource;
use Aheadworks\Langshop\Model\ResourceModel\Status\Collection;
use Aheadworks\Langshop\Model\ResourceModel\Status\CollectionFactory as StatusCollectionFactory;
use Aheadworks\Langshop\Model\ResourceModel\StatusFactory as StatusResourceFactory;
use Aheadworks\Langshop\Model\Service\Status as StatusService;
use Aheadworks\Langshop\Model\Status as StatusModel;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Webapi\Exception as WebapiException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    /**
     * @var StatusService
     */
    private StatusService $statusService;

    /**
     * @var CollectionProcessorInterface|MockObject
     */
    private $collectionProcessorMock;

    /**
     * @var MockObject|StatusCollectionFactory
     */
    private $statusCollectionFactoryMock;

    /**
     * @var MockObject|StatusResourceFactory
     */
    private $statusResourceFactoryMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->collectionProcessorMock = $this->createMock(CollectionProcessorInterface::class);
        $this->statusCollectionFactoryMock = $this->createMock(StatusCollectionFactory::class);
        $this->statusResourceFactoryMock= $this->createMock(StatusResourceFactory::class);

        $this->statusService = new StatusService(
            $this->collectionProcessorMock,
            $this->statusCollectionFactoryMock,
            $this->statusResourceFactoryMock
        );
    }

    /**
     * Test 'getList' method
     *
     * @return void
     */
    public function testGetList(): void
    {
        $statusCollection = $this->createMock(Collection::class);
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $statuses = [$this->createMock(StatusInterface::class)];

        $this->statusCollectionFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($statusCollection);
        $this->collectionProcessorMock
            ->expects($this->once())
            ->method('process')
            ->with($searchCriteria, $statusCollection);
        $statusCollection
            ->expects($this->once())
            ->method('getItems')
            ->willReturn($statuses);

        $this->assertSame($statuses, $this->statusService->getList($searchCriteria));
    }

    /**
     * Test 'save' method
     *
     * @return void
     * @throws WebapiException
     */
    public function testSave(): void
    {
        $statusResource = $this->createMock(StatusResource::class);
        $status = $this->createMock(StatusModel::class);

        $this->statusResourceFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($statusResource);

        $statusResource
            ->expects($this->once())
            ->method('save')
            ->with($status)
            ->willReturn($statusResource);

        $this->statusService->save($status);
    }

    /**
     * Test 'delete' method
     *
     * @return void
     * @throws WebapiException
     */
    public function testDelete(): void
    {
        $statusResource = $this->createMock(StatusResource::class);
        $status = $this->createMock(StatusModel::class);

        $this->statusResourceFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($statusResource);

        $statusResource
            ->expects($this->once())
            ->method('delete')
            ->with($status)
            ->willReturn($statusResource);

        $this->statusService->delete($status);
    }
}
