<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FilterInterface;
use Magento\Framework\Model\AbstractModel;

class Filter extends AbstractModel implements FilterInterface
{
    /**
     * @inheritDoc
     */
    public function setField(string $field): FilterInterface
    {
        return $this->setData(self::FIELD, $field);
    }

    /**
     * @inheritDoc
     */
    public function getField(): string
    {
        return $this->getData(self::FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setValue(string $value): FilterInterface
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getValue(): string
    {
        return $this->getData(self::VALUE);
    }
}
