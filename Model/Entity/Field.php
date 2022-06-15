<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity;

use Magento\Framework\DataObject;

class Field extends DataObject
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
    private const IS_TITLE = 'is_title';
    private const VISIBLE_ON = 'visible_on';

    /**
     * Set code
     *
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): Field
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->getData(self::CODE);
    }

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): Field
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->getData(self::LABEL);
    }

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): Field
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Set is filterable
     *
     * @param bool $isFilterable
     * @return $this
     */
    public function setIsFilterable(bool $isFilterable): Field
    {
        return $this->setData(self::IS_FILTERABLE, $isFilterable);
    }

    /**
     * Is filterable
     *
     * @return bool
     */
    public function isFilterable(): bool
    {
        return $this->getData(self::IS_FILTERABLE);
    }

    /**
     * Set filter type
     *
     * @param string $filterType
     * @return $this
     */
    public function setFilterType(string $filterType): Field
    {
        return $this->setData(self::FILTER_TYPE, $filterType);
    }

    /**
     * Get filter type
     *
     * @return string
     */
    public function getFilterType(): string
    {
        return $this->getData(self::FILTER_TYPE);
    }

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder(int $sortOrder): Field
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder(): int
    {
        return (int) $this->getData(self::SORT_ORDER);
    }

    /**
     * Set is sortable
     *
     * @param bool $isSortable
     * @return $this
     */
    public function setIsSortable(bool $isSortable): Field
    {
        return $this->setData(self::IS_SORTABLE, $isSortable);
    }

    /**
     * Is sortable
     *
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->getData(self::IS_SORTABLE);
    }

    /**
     * Set is translatable
     *
     * @param bool $isTranslatable
     * @return $this
     */
    public function setIsTranslatable(bool $isTranslatable): Field
    {
        return $this->setData(self::IS_TRANSLATABLE, $isTranslatable);
    }

    /**
     * Is translatable
     *
     * @return bool
     */
    public function isTranslatable(): bool
    {
        return $this->getData(self::IS_TRANSLATABLE);
    }

    /**
     * Set is title
     *
     * @param bool $isTitle
     * @return $this
     */
    public function setIsTitle(bool $isTitle): Field
    {
        return $this->setData(self::IS_TITLE, $isTitle);
    }

    /**
     * Is title
     *
     * @return bool
     */
    public function isTitle(): bool
    {
        return (bool) $this->getData(self::IS_TITLE);
    }

    /**
     * Set visible areas for field
     *
     * @param string[] $areas
     * @return $this
     */
    public function setVisibleOn(array $areas): Field
    {
        return $this->setData(self::VISIBLE_ON, $areas);
    }

    /**
     * Get visible areas for field
     *
     * @return string[]
     */
    public function getVisibleOn(): array
    {
        return $this->getData(self::VISIBLE_ON);
    }
}
