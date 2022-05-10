<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\TranslatableResource;

interface FilterInterface
{
    /**
     * Constants for internal keys
     */
    const FIELD = 'field';
    const VALUE = 'value';

    /**
     * Set field
     *
     * @param string $field
     * @return $this
     */
    public function setField(string $field): FilterInterface;

    /**
     * Get field
     *
     * @return string
     */
    public function getField(): string;

    /**
     * Set value
     *
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): FilterInterface;

    /**
     * Get value
     *
     * @return string
     */
    public function getValue(): string;
}
