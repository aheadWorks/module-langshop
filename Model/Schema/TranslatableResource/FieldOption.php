<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Schema\TranslatableResource;

use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldOptionInterface;
use Magento\Framework\DataObject;

class FieldOption extends DataObject implements FieldOptionInterface
{
    private const VALUE = 'value';
    private const LABEL = 'label';

    /**
     * @inheritDoc
     */
    public function setValue(string $value): FieldOptionInterface
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

    /**
     * @inheritDoc
     */
    public function setLabel(string $label): FieldOptionInterface
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return $this->getData(self::LABEL);
    }
}
