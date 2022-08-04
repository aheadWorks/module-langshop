<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Unit\Model\Service;

use Aheadworks\Langshop\Api\Data\SchemaInterface;
use Aheadworks\Langshop\Api\Data\SchemaInterfaceFactory;
use Aheadworks\Langshop\Model\Schema\ProcessorInterface;
use Aheadworks\Langshop\Model\Service\Schema as SchemaService;
use Magento\Framework\Webapi\Exception as WebapiException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
    /**
     * @var SchemaService
     */
    private SchemaService $schemaService;

    /**
     * @var ProcessorInterface|MockObject
     */
    private $processorMock;

    /**
     * @var SchemaInterfaceFactory|MockObject
     */
    private $schemaFactoryMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->processorMock = $this->createMock(ProcessorInterface::class);
        $this->schemaFactoryMock = $this->createMock(SchemaInterfaceFactory::class);

        $this->schemaService = new SchemaService(
            $this->processorMock,
            $this->schemaFactoryMock
        );
    }

    /**
     * Test 'get' method
     *
     * @return void
     * @throws WebapiException
     */
    public function testGet(): void
    {
        $result = $schemaMock = $this->createMock(SchemaInterface::class);

        $this->schemaFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($schemaMock);

        $this->processorMock
            ->expects($this->once())
            ->method('process')
            ->with($schemaMock);

        $this->assertSame($result, $this->schemaService->get());
    }
}
