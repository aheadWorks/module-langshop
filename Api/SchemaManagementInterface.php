<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api;

use Aheadworks\Langshop\Api\Data\SchemaInterface;

interface SchemaManagementInterface
{
    /**
     * Retrieves the data schema for translatable resources
     *
     * @return \Aheadworks\Langshop\Api\Data\SchemaInterface
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function get(): SchemaInterface;
}
