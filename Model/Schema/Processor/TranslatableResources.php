<?php
namespace Aheadworks\Langshop\Model\Schema\Processor;

use Aheadworks\Langshop\Api\Data\SchemaInterface;
use Aheadworks\Langshop\Model\Entity\Converter;
use Aheadworks\Langshop\Model\Schema\ProcessorInterface;
use Aheadworks\Langshop\Model\Entity\Pool as EntityPool;

class TranslatableResources implements ProcessorInterface
{
    /**
     * @var Converter
     */
    private Converter $converter;

    /**
     * @var EntityPool
     */
    private EntityPool $entityPool;

    /**
     * @param Converter $converter
     * @param EntityPool $entityPool
     */
    public function __construct(
        Converter $converter,
        EntityPool $entityPool
    ) {
        $this->converter = $converter;
        $this->entityPool = $entityPool;
    }

    /**
     * @inheritDoc
     */
    public function process(SchemaInterface $schema): void
    {
        $entities = $this->entityPool->getAll();
        $translatableResources = $this->converter->convertAll($entities);
        $schema->setTranslatableResources($translatableResources);
    }
}
