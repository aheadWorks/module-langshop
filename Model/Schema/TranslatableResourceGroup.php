<?php
namespace Aheadworks\Langshop\Model\Schema;

use Aheadworks\Langshop\Api\Data\Schema\ResourceGroupInterface;
use Magento\Framework\Model\AbstractModel;

class TranslatableResourceGroup extends AbstractModel implements ResourceGroupInterface
{
    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @inheritDoc
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * @inheritDoc
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * @inheritDoc
     */
    public function setResources($resources)
    {
        return $this->setData(self::RESOURCES, $resources);
    }

    /**
     * @inheritDoc
     */
    public function getResources()
    {
        return $this->getData(self::RESOURCES);
    }
}
