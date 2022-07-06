<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Test\Unit\Model\Service;

use Aheadworks\Langshop\Api\Data\SchemaInterface;
use Aheadworks\Langshop\Api\Data\SchemaInterfaceFactory;
use Aheadworks\Langshop\Model\Schema\ProcessorInterface;
use Aheadworks\Langshop\Model\Service\Schema as SchemaService;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Exception as WebapiException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

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
     * @var MockObject|LoggerInterface
     */
    private $loggerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->processorMock = $this->createMock(ProcessorInterface::class);
        $this->schemaFactoryMock = $this->createMock(SchemaInterfaceFactory::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->schemaService = new SchemaService(
            $this->processorMock,
            $this->schemaFactoryMock,
            $this->loggerMock
        );
    }

    /**
     * Test 'get' method
     *
     * @param bool $throwException
     *
     * @return void
     * @dataProvider dataProvider
     * @throws WebapiException
     */
    public function testGet(bool $throwException): void
    {
        $result = $schemaMock = $this->createMock(SchemaInterface::class);

        $exceptionMessage = 'message';
        $localizedException = new LocalizedException(__($exceptionMessage));
        $webapiException = new WebapiException(__($exceptionMessage), 500, 500);

        $this->schemaFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($schemaMock);

        if ($throwException) {
            $this->processorMock
                ->expects($this->once())
                ->method('process')
                ->with($schemaMock)
                ->willThrowException($localizedException);
            $this->loggerMock
                ->expects($this->once())
                ->method('error')
                ->with($exceptionMessage);
            $this->expectExceptionObject($webapiException);
            $result = $webapiException;
        } else {
            $this->processorMock
                ->expects($this->once())
                ->method('process')
                ->with($schemaMock);
        }

        $this->assertSame($result, $this->schemaService->get());
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
