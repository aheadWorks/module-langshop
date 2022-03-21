<?php
namespace Aheadworks\Langshop\Api\Data\Schema\TranslatableResource;

interface SortingInterface
{
    const KEY = 'key';
    const LABEL = 'label';
    const FIELD = 'field';
    const DIRECTION = 'direction';

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
     * Set field
     *
     * @param string $field
     * @return $this
     */
    public function setField($field);

    /**
     * Get field
     *
     * @return string
     */
    public function getField();

    /**
     * Set direction
     *
     * @param string $direction
     * @return $this
     */
    public function setDirection($direction);

    /**
     * Get direction
     *
     * @return string
     */
    public function getDirection();
}
