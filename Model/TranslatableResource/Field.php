<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface;
use Magento\Framework\DataObject;

class Field extends DataObject implements FieldInterface
{
    /**
     * Constants for internal keys
     */
    private const KEY = 'key';
    private const VALUE = 'value';

    /**
     * Get key
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->getData(self::KEY);
    }

    /**
     * Set key
     *
     * @param string $key
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface
     */
    public function setKey(string $key): FieldInterface
    {
        return $this->setData(self::KEY, $key);
    }

    /**
     * Get value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    /**
     * Set value
     *
     * @param mixed $value
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface
     */
    public function setValue($value): FieldInterface
    {
        return $this->setData(self::VALUE, $value);
    }
}
