<?php
namespace Aheadworks\Langshop\Api\Data\Schema\ResourceGroup;

interface ResourceInterface
{
    const RESOURCE = 'resource';
    const SORT_ORDER = 'sort_order';

    /**
     * Set resource
     *
     * @param string $resource
     * @return $this
     */
    public function setResource($resource);

    /**
     * Get resource
     *
     * @return string
     */
    public function getResource();

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder();
}
