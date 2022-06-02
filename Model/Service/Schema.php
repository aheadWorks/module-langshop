<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\SchemaInterface;
use Aheadworks\Langshop\Api\Data\SchemaInterfaceFactory;
use Aheadworks\Langshop\Api\SchemaManagementInterface;
use Aheadworks\Langshop\Model\Schema\ProcessorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Exception as WebapiException;
use Psr\Log\LoggerInterface;

class Schema implements SchemaManagementInterface
{
    /**
     * @var ProcessorInterface
     */
    private ProcessorInterface $processor;

    /**
     * @var SchemaInterfaceFactory
     */
    private SchemaInterfaceFactory $schemaFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param ProcessorInterface $processor
     * @param SchemaInterfaceFactory $schemaFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ProcessorInterface $processor,
        SchemaInterfaceFactory $schemaFactory,
        LoggerInterface $logger
    ) {
        $this->processor = $processor;
        $this->schemaFactory = $schemaFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function get(): SchemaInterface
    {
        $schema = $this->schemaFactory->create();

        try {
            $this->processor->process($schema);
        } catch (LocalizedException $exception) {
            $this->logger->error($exception->getMessage());
            throw new WebapiException(__($exception->getMessage()), 500, 500);
        }

        return $schema;
    }
}
