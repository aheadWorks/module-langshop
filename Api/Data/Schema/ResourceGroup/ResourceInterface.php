<?php
namespace Aheadworks\Langshop\Api\Data\Schema\ResourceGroup;

interface ResourceInterface
{
    /**
     * Set resource
     *
     * @param string $resource
     * @return $this
     */
    public function setResource(string $resource): ResourceInterface;

    /**
     * Get resource
     *
     * @return string
     */
    public function getResource(): string;

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder(int $sortOrder): ResourceInterface;

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder(): int;
}
