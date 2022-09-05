<?php
namespace Aheadworks\Langshop\Api\Data;

interface SchemaInterface
{
    /**
     * Set version
     *
     * @param string $version
     * @return $this
     */
    public function setVersion($version);

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion();

    /**
     * Set translatable resources
     *
     * @param \Aheadworks\Langshop\Api\Data\Schema\ResourceInterface[] $resources
     * @return $this
     */
    public function setTranslatableResources($resources);

    /**
     * Get translatable resources
     *
     * @return \Aheadworks\Langshop\Api\Data\Schema\ResourceInterface[]
     */
    public function getTranslatableResources();

    /**
     * Set possibilities
     *
     * @param string[] $possibilities
     * @return $this
     */
    public function setPossibilities($possibilities);

    /**
     * Get possibilities
     *
     * @return string[]
     */
    public function getPossibilities();

    /**
     * Set translatable resource groups
     *
     * @param \Aheadworks\Langshop\Api\Data\Schema\ResourceGroupInterface[] $resourceGroups
     * @return $this
     */
    public function setTranslatableResourceGroups($resourceGroups);

    /**
     * Get translatable resource groups
     *
     * @return \Aheadworks\Langshop\Api\Data\Schema\ResourceGroupInterface[]
     */
    public function getTranslatableResourceGroups();
}
