<?php
namespace Aheadworks\Langshop\Model\Schema\Processor;

use Aheadworks\Langshop\Api\Data\SchemaInterface;
use Aheadworks\Langshop\Model\Schema\ProcessorInterface;
use Aheadworks\Langshop\Model\Entity\Pool as EntityPool;

class TranslatableResources implements ProcessorInterface
{
    /**
     * @var EntityPool
     */
    private $entityPool;

    /**
     * @param EntityPool $entityPool
     */
    public function __construct(
        EntityPool $entityPool
    ) {
        $this->entityPool = $entityPool;
    }

    /**
     * @inheritDoc
     */
    public function process(SchemaInterface $schema)
    {
        $entities = $this->entityPool->getAll();
        //todo: add resource converter LSM2-36
        $translatableResources = [];
        $schema->setTranslatableResources($translatableResources);
    }
}
