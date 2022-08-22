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
     * @inheritDoc
     */
    public function getKey(): ?string
    {
        return $this->getData(self::KEY);
    }

    /**
     * @inheritDoc
     */
    public function setKey(string $key): FieldInterface
    {
        return $this->setData(self::KEY, $key);
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    /**
     * @inheritDoc
     */
    public function setValue($value): FieldInterface
    {
        return $this->setData(self::VALUE, $value);
    }
}
