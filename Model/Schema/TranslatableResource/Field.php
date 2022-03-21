<?php
namespace Aheadworks\Langshop\Model\Schema\TranslatableResource;

use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldInterface;
use Magento\Framework\Model\AbstractModel;

class Field extends AbstractModel implements FieldInterface
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
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Set is translatable
     *
     * @param bool $isTranslatable
     * @return $this
     */
    public function setIsTranslatable($isTranslatable)
    {
        return $this->setData(self::IS_TRANSLATABLE, $isTranslatable);
    }

    /**
     * Is translatable
     *
     * @return bool
     */
    public function isTranslatable()
    {
        return $this->getData(self::IS_TRANSLATABLE);
    }

    /**
     * Set filter
     *
     * @param string $filter
     * @return $this
     */
    public function setFilter($filter)
    {
        return $this->setData(self::FILTER, $filter);
    }

    /**
     * Get filter
     *
     * @return string
     */
    public function getFilter()
    {
        return $this->getData(self::FILTER);
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
}
