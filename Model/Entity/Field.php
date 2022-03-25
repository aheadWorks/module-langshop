<?php
namespace Aheadworks\Langshop\Model\Entity;

use Magento\Framework\Model\AbstractModel;

class Field extends AbstractModel
{
    const RESOURCE_TYPE = 'resource_type';
    const CODE = 'code';
    const LABEL = 'label';
    const TYPE = 'type';
    const IS_FILTERABLE = 'is_filterable';
    const FILTER_TYPE = 'filter_type';
    const SORT_ORDER = 'sort_order';
    const IS_SORTABLE = 'is_sortable';
    const SORTING_LABEL_LIST = 'sorting_label_list';
    const IS_TRANSLATABLE = 'is_translatable';

    /**
     * Set resource type
     *
     * @param string $resourceType
     * @return $this
     */
    public function setResourceType($resourceType)
    {
        return $this->setData(self::RESOURCE_TYPE, $resourceType);
    }

    /**
     * Get resource type
     *
     * @return string
     */
    public function getResourceType()
    {
        return  $this->getData(self::RESOURCE_TYPE);
    }

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
     * Set sorting label list
     *
     * @param string[] $sortingLabelList
     * @return $this
     */
    public function setSortingLabelList($sortingLabelList)
    {
        return $this->setData(self::SORTING_LABEL_LIST, $sortingLabelList);
    }

    /**
     * Get sorting label list
     *
     * @return string[]
     */
    public function getSortingLabelList()
    {
        return $this->getData(self::SORTING_LABEL_LIST);
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
}
