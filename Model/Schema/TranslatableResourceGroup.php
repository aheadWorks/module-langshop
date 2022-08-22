<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Schema;

use Aheadworks\Langshop\Api\Data\Schema\ResourceGroupInterface;
use Magento\Framework\DataObject;

class TranslatableResourceGroup extends DataObject implements ResourceGroupInterface
{
    /**
     * Constants for internal keys
     */
    private const ID = 'id';
    private const TITLE = 'title';
    private const SORT_ORDER = 'sortOrder';
    private const RESOURCES = 'resources';

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * Set resources
     *
     * @param \Aheadworks\Langshop\Api\Data\Schema\ResourceGroup\ResourceInterface[] $resources
     * @return $this
     */
    public function setResources($resources)
    {
        return $this->setData(self::RESOURCES, $resources);
    }

    /**
     * Get resources
     *
     * @return \Aheadworks\Langshop\Api\Data\Schema\ResourceGroup\ResourceInterface[]
     */
    public function getResources()
    {
        return $this->getData(self::RESOURCES);
    }
}
