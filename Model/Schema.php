<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model;

use Aheadworks\Langshop\Api\Data\SchemaInterface;
use Magento\Framework\DataObject;

class Schema extends DataObject implements SchemaInterface
{
    /**
     * Constants for internal keys
     */
    private const VERSION = 'version';
    private const TRANSLATABLE_RESOURCES = 'translatableResources';
    private const POSSIBILITIES = 'possibilities';
    private const TRANSLATABLE_RESOURCE_GROUPS = 'translatableResourceGroups';

    /**
     * Set version
     *
     * @param string $version
     * @return $this
     */
    public function setVersion($version)
    {
        return $this->setData(self::VERSION, $version);
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->getData(self::VERSION);
    }

    /**
     * Set translatable resources
     *
     * @param \Aheadworks\Langshop\Api\Data\Schema\ResourceInterface[] $resources
     * @return $this
     */
    public function setTranslatableResources($resources)
    {
        return $this->setData(self::TRANSLATABLE_RESOURCES, $resources);
    }

    /**
     * Get translatable resources
     *
     * @return \Aheadworks\Langshop\Api\Data\Schema\ResourceInterface[]
     */
    public function getTranslatableResources()
    {
        return $this->getData(self::TRANSLATABLE_RESOURCES);
    }

    /**
     * Set possibilities
     *
     * @param string[] $possibilities
     * @return $this
     */
    public function setPossibilities($possibilities)
    {
        return $this->setData(self::POSSIBILITIES, $possibilities);
    }

    /**
     * Get possibilities
     *
     * @return string[]
     */
    public function getPossibilities()
    {
        return $this->getData(self::POSSIBILITIES);
    }

    /**
     * Set translatable resource groups
     *
     * @param \Aheadworks\Langshop\Api\Data\Schema\ResourceGroupInterface[] $resourceGroups
     * @return $this
     */
    public function setTranslatableResourceGroups($resourceGroups)
    {
        return $this->setData(self::TRANSLATABLE_RESOURCE_GROUPS, $resourceGroups);
    }

    /**
     * Get translatable resource groups
     *
     * @return \Aheadworks\Langshop\Api\Data\Schema\ResourceGroupInterface[]
     */
    public function getTranslatableResourceGroups()
    {
        return $this->getData(self::TRANSLATABLE_RESOURCE_GROUPS);
    }
}
