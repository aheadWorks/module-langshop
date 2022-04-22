<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Field;

class Pool
{
    /**
     * @var CustomFieldInterface[]
     */
    private array $fields;

    /**
     * @param CustomFieldInterface[] $fields
     */
    public function __construct(
        array $fields
    ) {
        $this->fields = $fields;
    }

    /**
     * Return field
     *
     * @param string $fieldCode
     * @return CustomFieldInterface
     */
    public function getField(string $fieldCode)
    {
        return isset($this->fields[$fieldCode])
            ? $this->fields[$fieldCode]
            : $this->fields['default'];
    }
}
