<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Framework\Webapi;

use Exception;
use Magento\Framework\Webapi\ErrorProcessor as WebapiErrorProcessor;
use Magento\Framework\Webapi\Exception as WebapiException;
use Magento\Webapi\Controller\Rest\InputParamsResolver;
use Psr\Log\LoggerInterface;

class ErrorProcessor
{
    /**
     * @var InputParamsResolver
     */
    private InputParamsResolver $inputParamsResolver;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param InputParamsResolver $inputParamsResolver
     * @param LoggerInterface $logger
     */
    public function __construct(
        InputParamsResolver $inputParamsResolver,
        LoggerInterface $logger
    ) {
        $this->inputParamsResolver = $inputParamsResolver;
        $this->logger = $logger;
    }

    /**
     * Converts the plain exceptions to the webapi ones
     *
     * @param WebapiErrorProcessor $errorProcessor
     * @param Exception $exception
     * @return array
     * @throws WebapiException
     */
    public function beforeMaskException(
        WebapiErrorProcessor $errorProcessor,
        Exception $exception
    ): array {
        if (str_starts_with(
            $this->inputParamsResolver->getRoute()->getServiceClass(),
            'Aheadworks\\Langshop'
        )) {
            $exception = new WebapiException(__($exception->getMessage()), 500, 500);
            $this->logger->error((string) $exception);
        }

        return [$exception];
    }
}
