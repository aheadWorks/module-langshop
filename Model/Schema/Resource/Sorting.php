<?php
namespace Aheadworks\Langshop\Model\Schema\Resource;


use Aheadworks\Langshop\Api\Data\Schema\Resource\SortingInterface;
use Magento\Framework\Model\AbstractModel;

class Sorting extends AbstractModel implements SortingInterface
{
    /**
     * Set key
     *
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        return $this->setData(self::KEY, $key);
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return  $this->getData(self::KEY);
    }

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->getData(self::LABEL);
    }

    /**
     * Set field
     *
     * @param string $field
     * @return $this
     */
    public function setField($field)
    {
        return $this->setData(self::FIELD, $field);
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField()
    {
        return $this->getData(self::FIELD);
    }

    /**
     * Set direction
     *
     * @param string $direction
     * @return $this
     */
    public function setDirection($direction)
    {
        return $this->setData(self::DIRECTION, $direction);
    }

    /**
     * Get direction
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->getData(self::DIRECTION);
    }
}
