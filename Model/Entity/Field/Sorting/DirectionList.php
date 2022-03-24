<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Sorting;

class DirectionList
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(
        array $data = []
    ) {
        $this->data = $data;
    }

    /**
     * Get sorting directions and label ending
     *
     * @param string $fieldType
     * @return array
     */
    public function get($fieldType)
    {
        return $this->data[$fieldType] ?? $this->data['default'];
    }
}
