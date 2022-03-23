<?php
namespace Aheadworks\Langshop\Model\Entity;

use Aheadworks\Langshop\Model\Entity;
use Magento\Framework\Exception\LocalizedException;

class Pool
{
    /**
     * @var Entity[]
     */
    private $entityList;

    /**
     * @param Entity[] $entityList
     */
    public function __construct(
        array $entityList = []
    ) {
        $this->entityList = $entityList;
    }

    /**
     * Get all
     *
     * @return Entity[]
     */
    public function getAll()
    {
        return $this->entityList;
    }

    /**
     * Get by type
     *
     * @param string $type
     * @return Entity
     * @throws LocalizedException
     */
    public function getByType($type)
    {
        if (!isset($this->entityList[$type])) {
            throw new LocalizedException(__('No such entity with type = %1', $type));
        }

        return $this->entityList[$type];
    }
}
