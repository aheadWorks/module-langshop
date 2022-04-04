<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterface;
use Magento\Framework\Model\AbstractModel;

class Field extends AbstractModel implements FieldInterface
{
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
