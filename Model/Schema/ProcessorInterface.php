<?php
namespace Aheadworks\Langshop\Model\Schema;

use Aheadworks\Langshop\Api\Data\SchemaInterface;

interface ProcessorInterface
{
    /**
     * Process schema data
     *
     * @param \Aheadworks\Langshop\Api\Data\SchemaInterface $schema
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function process(SchemaInterface $schema);
}
