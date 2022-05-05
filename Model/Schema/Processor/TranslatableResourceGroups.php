<?php
namespace Aheadworks\Langshop\Model\Schema\Processor;

use Aheadworks\Langshop\Api\Data\SchemaInterface;
use Aheadworks\Langshop\Model\Schema\ProcessorInterface;
use Aheadworks\Langshop\Model\Schema\ResourceGroup\Pool as GroupPool;

class TranslatableResourceGroups implements ProcessorInterface
{
    /**
     * @var GroupPool
     */
    private GroupPool $groupPool;

    /**
     * @param GroupPool $groupPool
     */
    public function __construct(
        GroupPool $groupPool
    ) {
        $this->groupPool = $groupPool;
    }

    /**
     * @inheritDoc
     */
    public function process(SchemaInterface $schema): void
    {
        $schema->setTranslatableResourceGroups($this->groupPool->getAll());
    }
}
