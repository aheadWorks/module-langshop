<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\Schema\TranslatableResource;

interface FieldOptionInterface
{
    /**
     * Set value
     *
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): FieldOptionInterface;

    /**
     * Get value
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): FieldOptionInterface;

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string;
}
