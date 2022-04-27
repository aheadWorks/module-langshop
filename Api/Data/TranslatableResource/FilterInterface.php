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
     * @param string $field
     * @return $this
     */
    public function setField(string $field): FilterInterface;

    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): FilterInterface;

    /**
     * @return string
     */
    public function getValue(): string;
}
