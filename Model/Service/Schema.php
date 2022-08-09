<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\SchemaInterface;
use Aheadworks\Langshop\Api\Data\SchemaInterfaceFactory;
use Aheadworks\Langshop\Api\SchemaManagementInterface;
use Aheadworks\Langshop\Model\Schema\ProcessorInterface;

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
    public function get(): SchemaInterface
    {
        $schema = $this->schemaFactory->create();
        $this->processor->process($schema);

        return $schema;
    }
}
