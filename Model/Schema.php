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
     * @inheritDoc
     */
    public function setVersion($version)
    {
        return $this->setData(self::VERSION, $version);
    }

    /**
     * @inheritDoc
     */
    public function getVersion()
    {
        return $this->getData(self::VERSION);
    }

    /**
     * @inheritDoc
     */
    public function setTranslatableResources($resources)
    {
        return $this->setData(self::TRANSLATABLE_RESOURCES, $resources);
    }

    /**
     * @inheritDoc
     */
    public function getTranslatableResources()
    {
        return $this->getData(self::TRANSLATABLE_RESOURCES);
    }

    /**
     * @inheritDoc
     */
    public function setPossibilities($possibilities)
    {
        return $this->setData(self::POSSIBILITIES, $possibilities);
    }

    /**
     * @inheritDoc
     */
    public function getPossibilities()
    {
        return $this->getData(self::POSSIBILITIES);
    }

    /**
     * @inheritDoc
     */
    public function setTranslatableResourceGroups($resourceGroups)
    {
        return $this->setData(self::TRANSLATABLE_RESOURCE_GROUPS, $resourceGroups);
    }

    /**
     * @inheritDoc
     */
    public function getTranslatableResourceGroups()
    {
        return $this->getData(self::TRANSLATABLE_RESOURCE_GROUPS);
    }
}
