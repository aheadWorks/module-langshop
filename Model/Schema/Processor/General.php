<?php
namespace Aheadworks\Langshop\Model\Schema\Processor;

use Aheadworks\Langshop\Api\Data\SchemaInterface;
use Aheadworks\Langshop\Model\Schema\ProcessorInterface;

class General implements ProcessorInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(
        array $data = []
    ) {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function process(SchemaInterface $schema)
    {
        foreach ($this->data as $key => $value) {
            $schema->setData($key, $value);
        }
    }
}
