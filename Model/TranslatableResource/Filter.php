<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FilterInterface;
use Magento\Framework\DataObject;

class Filter extends DataObject implements FilterInterface
{
    /**
     * Constants for internal keys
     */
    private const FIELD = 'field';
    private const VALUE = 'value';

    /**
     * Set field
     *
     * @param string $field
     * @return $this
     */
    public function setField(string $field): FilterInterface
    {
        return $this->setData(self::FIELD, $field);
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField(): string
    {
        return $this->getData(self::FIELD);
    }

    /**
     * Set value
     *
     * @param string[] $value
     * @return $this
     */
    public function setValue(array $value): FilterInterface
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * Get value
     *
     * @return string[]
     */
    public function getValue(): array
    {
        return $this->getData(self::VALUE);
    }
}
