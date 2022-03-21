<?php
namespace Aheadworks\Langshop\Api\Data;

use Aheadworks\Langshop\Api\Data\Schema\ResourceGroupInterface;
use Aheadworks\Langshop\Api\Data\Schema\ResourceInterface;

interface SchemaInterface
{
    const VERSION = 'version';
    const TRANSLATABLE_RESOURCES = 'translatableResources';
    const POSSIBILITIES = 'possibilities';
    const TRANSLATABLE_RESOURCE_GROUPS = 'translatableResourceGroups';

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
     * @param ResourceInterface[] $resources
     * @return $this
     */
    public function setTranslatableResources($resources);

    /**
     * Get translatable resources
     *
     * @return ResourceInterface[]
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
     * @param ResourceGroupInterface[] $resourceGroups
     * @return $this
     */
    public function setTranslatableResourceGroups($resourceGroups);

    /**
     * Get translatable resource groups
     *
     * @return ResourceGroupInterface[]
     */
    public function getTranslatableResourceGroups();
}
