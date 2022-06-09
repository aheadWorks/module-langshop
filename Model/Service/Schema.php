<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\SchemaInterface;
use Aheadworks\Langshop\Api\Data\SchemaInterfaceFactory;
use Aheadworks\Langshop\Api\SchemaManagementInterface;
use Aheadworks\Langshop\Model\Schema\ProcessorInterface;
use Magento\Framework\Encryption\Encryptor;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir\Reader as DirReader;
use Magento\Framework\Module\FullModuleList;
use Magento\Framework\Translate;
use Magento\Framework\Webapi\Exception as WebapiException;
use Psr\Log\LoggerInterface;

class Schema implements SchemaManagementInterface
{
    /**
     * @var ProcessorInterface
     */
    private ProcessorInterface $processor;

    /**
     * @var Csv
     */
    private Csv $csv;

    /**
     * @var DirReader
     */
    private DirReader $dirReader;

    /**
     * @var Encryptor
     */
    private Encryptor $encryptor;

    /**
     * @var SchemaInterfaceFactory
     */
    private SchemaInterfaceFactory $schemaFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    private Translate $translate;

    private FullModuleList $moduleList;

    /**
     * @param ProcessorInterface $processor
     * @param SchemaInterfaceFactory $schemaFactory
     * @param Csv $csv
     * @param DirReader $dirReader
     * @param Encryptor $encryptor
     * @param Translate $translate
     * @param LoggerInterface $logger
     */
    public function __construct(
        ProcessorInterface $processor,
        SchemaInterfaceFactory $schemaFactory,
        Csv $csv,
        DirReader $dirReader,
        Encryptor $encryptor,
        Translate $translate,
        FullModuleList $moduleList,
        LoggerInterface $logger
    ) {
        $this->processor = $processor;
        $this->schemaFactory = $schemaFactory;
        $this->csv = $csv;
        $this->encryptor = $encryptor;
        $this->dirReader = $dirReader;
        $this->translate = $translate;
        $this->moduleList = $moduleList;
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
