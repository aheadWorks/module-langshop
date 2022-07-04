<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\TranslatableResource;

interface FilterInterface
{
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
     * @param string[] $value
     * @return $this
     */
    public function setValue(array $value): FilterInterface;

    /**
     * Get value
     *
     * @return string[]
     */
    public function getValue(): array;
}
