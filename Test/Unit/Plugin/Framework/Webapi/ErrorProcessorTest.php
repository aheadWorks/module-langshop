<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Unit\Plugin\Framework\Webapi;

use Aheadworks\Langshop\Plugin\Framework\Webapi\ErrorProcessor;
use Exception;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Webapi\ErrorProcessor as WebapiErrorProcessor;
use Magento\Framework\Webapi\Exception as WebapiException;
use Magento\Webapi\Controller\Rest\InputParamsResolver;
use Magento\Webapi\Controller\Rest\Router\Route;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ErrorProcessorTest extends TestCase
{
    /**
     * @var ErrorProcessor
     */
    private ErrorProcessor $plugin;

    /**
     * @var InputParamsResolver|MockObject
     */
    private $inputParamsResolverMock;

    /**
     * @var LoggerInterface|MockObject
     */
    private $loggerMock;

    /**
     * @var WebapiErrorProcessor|MockObject
     */
    private $errorProcessorMock;

    protected function setUp(): void
    {
        $this->inputParamsResolverMock = $this->createMock(InputParamsResolver::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->errorProcessorMock = $this->createMock(WebapiErrorProcessor::class);

        $requestMock = $this->createMock(RequestInterface::class);
        $requestMock->method('getParams')->willReturn([]);

        $this->plugin = new ErrorProcessor(
            $this->inputParamsResolverMock,
            $requestMock,
            $this->loggerMock
        );
    }

    public function testBeforeMaskExceptionKeepsExceptionWhenRouteIsNotMatched(): void
    {
        $exception = new WebapiException(
            __('Request does not match any route.'),
            0,
            WebapiException::HTTP_NOT_FOUND
        );
        $this->inputParamsResolverMock->method('getRoute')->willThrowException($exception);
        $this->loggerMock->expects($this->never())->method('error');

        $this->assertSame(
            [$exception],
            $this->plugin->beforeMaskException($this->errorProcessorMock, $exception)
        );
    }

    public function testBeforeMaskExceptionConvertsExceptionOfOwnRoute(): void
    {
        $this->mockRouteServiceClass('Aheadworks\Langshop\Api\StatusRepositoryInterface');
        $this->loggerMock->expects($this->once())->method('error');

        $result = $this->plugin->beforeMaskException($this->errorProcessorMock, new Exception('Failure'));

        $this->assertCount(1, $result);
        $this->assertInstanceOf(WebapiException::class, $result[0]);
        $this->assertSame(WebapiException::HTTP_INTERNAL_ERROR, $result[0]->getHttpCode());
    }

    public function testBeforeMaskExceptionKeepsExceptionOfForeignRoute(): void
    {
        $this->mockRouteServiceClass('Magento\Catalog\Api\ProductRepositoryInterface');
        $this->loggerMock->expects($this->never())->method('error');
        $exception = new Exception('Failure');

        $this->assertSame(
            [$exception],
            $this->plugin->beforeMaskException($this->errorProcessorMock, $exception)
        );
    }

    private function mockRouteServiceClass(string $serviceClass): void
    {
        $routeMock = $this->createMock(Route::class);
        $routeMock->method('getServiceClass')->willReturn($serviceClass);
        $this->inputParamsResolverMock->method('getRoute')->willReturn($routeMock);
    }
}
