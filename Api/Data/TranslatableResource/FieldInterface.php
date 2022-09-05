<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\TranslatableResource;

interface FieldInterface
{
    /**
     * Get key
     *
     * @return string|null
     */
    public function getKey(): ?string;

    /**
     * Set key
     *
     * @param string $key
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface
     */
    public function setKey(string $key): FieldInterface;

    /**
     * Get value
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set value
     *
     * @param mixed $value
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface
     */
    public function setValue($value): FieldInterface;
}
