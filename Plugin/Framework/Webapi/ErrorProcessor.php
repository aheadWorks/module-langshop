<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Framework\Webapi;

use Exception;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Webapi\ErrorProcessor as WebapiErrorProcessor;
use Magento\Framework\Webapi\Exception as WebapiException;
use Magento\Webapi\Controller\Rest\InputParamsResolver;
use Psr\Log\LoggerInterface;

class ErrorProcessor
{
    /**
     * @param InputParamsResolver $inputParamsResolver
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly InputParamsResolver $inputParamsResolver,
        private readonly RequestInterface $request,
        private readonly LoggerInterface $logger
    ) {}

    /**
     * Converts the plain exceptions to the webapi ones
     *
     * @param WebapiErrorProcessor $errorProcessor
     * @param Exception $exception
     * @return array
     * @throws WebapiException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeMaskException(
        WebapiErrorProcessor $errorProcessor,
        Exception $exception
    ): array {
        if ($this->isOurNamespace() && !($exception instanceof WebapiException)) {
            $exception = new WebapiException(
                __($exception->getMessage()),
                WebapiException::HTTP_INTERNAL_ERROR,
                WebapiException::HTTP_INTERNAL_ERROR
            );

            $this->logger->error((string) $exception, [
                'request' => $this->request->getParams()
            ]);
        }

        return [$exception];
    }

    /**
     * Checks if we have to process the incoming exception
     *
     * @return bool
     * @throws WebapiException
     */
    private function isOurNamespace(): bool
    {
        $namespace = explode('\\', __NAMESPACE__);

        return str_starts_with(
            $this->inputParamsResolver->getRoute()->getServiceClass(),
            sprintf('%s\%s', $namespace[0], $namespace[1])
        );
    }
}
