<?php
namespace Aheadworks\Langshop\Api\Data\Schema\TranslatableResource;

interface FieldInterface
{
    public const KEY = 'key';
    public const LABEL = 'label';
    public const TYPE = 'type';
    public const IS_TRANSLATABLE = 'isTranslatable';
    public const VISIBLE_ON = 'visible_on';
    public const FILTER = 'filter';
    public const SORT_ORDER = 'sortOrder';

    /**
     * Set key
     *
     * @param string $key
     * @return $this
     */
    public function setKey($key);

    /**
     * Get key
     *
     * @return string
     */
    public function getKey();

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label);

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * Get type
     *
     * @return string
     */
    public function getType();

    /**
     * Set is translatable
     *
     * @param bool $isTranslatable
     * @return $this
     */
    public function setIsTranslatable($isTranslatable);

    /**
     * Is translatable
     *
     * @return bool
     */
    public function isTranslatable();

    /**
     * Set visible areas for field
     *
     * @param string[] $areas
     * @return $this
     */
    public function setVisibleOn(array $areas);

    /**
     * Get visible areas for field
     *
     * @return string[]
     */
    public function getVisibleOn();

    /**
     * Set filter
     *
     * @param string $filter
     * @return $this
     */
    public function setFilter($filter);

    /**
     * Get filter
     *
     * @return string
     */
    public function getFilter();

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder();
}
