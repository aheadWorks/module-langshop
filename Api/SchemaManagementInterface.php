<?php
namespace Aheadworks\Langshop\Api;

interface SchemaManagementInterface
{
    /**
     * Retrieves the data schema for translatable resources
     *
     * @return \Aheadworks\Langshop\Api\Data\SchemaInterface
     */
    public function get();
}
