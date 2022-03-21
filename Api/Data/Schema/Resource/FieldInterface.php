<?php
namespace Aheadworks\Langshop\Api\Data\Schema\Resource;

interface FieldInterface
{
    const KEY = 'key';
    const LABEL = 'label';
    const TYPE = 'type';
    const IS_TRANSLATABLE = 'isTranslatable';
    const FILTER = 'filter';
    const SORT_ORDER = 'sortOrder';

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
