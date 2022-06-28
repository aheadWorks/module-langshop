<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource;

interface CollectionInterface
{
    /**
     * Set related resource type
     *
     * @param string $resourceType
     * @return $this
     */
    public function setResourceType(string $resourceType);

    /**
     * Retrieve related resource type
     *
     * @return string
     */
    public function getResourceType(): string;

    /**
     * Set store scope
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * Retrieve store scope
     *
     * @return int
     */
    public function getStoreId();
}
