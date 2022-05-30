<?php
namespace Aheadworks\Langshop\Model\Entity;

use Magento\Framework\Model\AbstractModel;

class Field extends AbstractModel
{
    /**
     * Constants for internal keys
     */
    private const CODE = 'code';
    private const LABEL = 'label';
    private const TYPE = 'type';
    private const IS_FILTERABLE = 'is_filterable';
    private const FILTER_TYPE = 'filter_type';
    private const SORT_ORDER = 'sort_order';
    private const IS_SORTABLE = 'is_sortable';
    private const IS_TRANSLATABLE = 'is_translatable';
    private const IS_NECESSARY = 'is_necessary';

    /**
     * Set code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
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
     * Set is filterable
     *
     * @param bool $isFilterable
     * @return $this
     */
    public function setIsFilterable($isFilterable)
    {
        return $this->setData(self::IS_FILTERABLE, $isFilterable);
    }

    /**
     * Is filterable
     *
     * @return bool
     */
    public function isFilterable()
    {
        return $this->getData(self::IS_FILTERABLE);
    }

    /**
     * Set filter type
     *
     * @param string $filterType
     * @return $this
     */
    public function setFilterType($filterType)
    {
        return $this->setData(self::FILTER_TYPE, $filterType);
    }

    /**
     * Get filter type
     *
     * @return string
     */
    public function getFilterType()
    {
        return $this->getData(self::FILTER_TYPE);
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
     * Set is sortable
     *
     * @param bool $isSortable
     * @return $this
     */
    public function setIsSortable($isSortable)
    {
        return $this->setData(self::IS_SORTABLE, $isSortable);
    }

    /**
     * Is sortable
     *
     * @return bool
     */
    public function isSortable()
    {
        return $this->getData(self::IS_SORTABLE);
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
     * Set is necessary
     *
     * @param bool $isNecessary
     * @return $this
     */
    public function setIsNecessary($isNecessary)
    {
        return $this->setData(self::IS_NECESSARY, $isNecessary);
    }

    /**
     * Is necessary
     *
     * In the case when a field is not translatable
     * but should be in the array of resource fields
     * it should return true
     *
     * @return bool
     */
    public function isNecessary()
    {
        return $this->getData(self::IS_NECESSARY);
    }
}
