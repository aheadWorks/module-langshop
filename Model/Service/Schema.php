<?php
namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\SchemaInterfaceFactory;
use Aheadworks\Langshop\Api\SchemaManagementInterface;
use Aheadworks\Langshop\Model\Schema\ProcessorInterface;

class Schema implements SchemaManagementInterface
{
    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * @var SchemaInterfaceFactory
     */
    private $schemaFactory;

    /**
     * @param ProcessorInterface $processor
     * @param SchemaInterfaceFactory $schemaFactory
     */
    public function __construct(
        ProcessorInterface $processor,
        SchemaInterfaceFactory $schemaFactory
    ) {
        $this->processor = $processor;
        $this->schemaFactory = $schemaFactory;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        $schema = $this->schemaFactory->create();
        $this->processor->process($schema);

        return $schema;
    }
}
