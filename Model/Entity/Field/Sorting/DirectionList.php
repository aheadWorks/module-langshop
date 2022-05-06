<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Field\Sorting;

class DirectionList
{
    /**
     * @var array
     */
    private array $data;

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
    public function get(string $fieldType): array
    {
        return $this->data[$fieldType] ?? $this->data['default'];
    }
}
