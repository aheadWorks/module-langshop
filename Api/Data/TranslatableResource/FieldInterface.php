<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\TranslatableResource;

interface FieldInterface
{
    /**
     * Constants for internal keys
     */
    const KEY = 'key';
    const VALUE = 'value';

    /**
     * @return string|null
     */
    public function getKey(): ?string;

    /**
     * @param string $key
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface
     */
    public function setKey(string $key): FieldInterface;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $value
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface
     */
    public function setValue($value): FieldInterface;
}
