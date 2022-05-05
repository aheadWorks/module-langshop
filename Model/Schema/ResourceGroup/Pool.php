<?php
namespace Aheadworks\Langshop\Model\Schema\ResourceGroup;

use Aheadworks\Langshop\Api\Data\Schema\ResourceGroupInterface;

class Pool
{
    /**
     * @var ResourceGroupInterface[]
     */
    private array $groupList;

    /**
     * @param ResourceGroupInterface[] $groupList
     */
    public function __construct(
        array $groupList = []
    ) {
        $this->groupList = $groupList;
    }

    /**
     * Get all
     *
     * @return ResourceGroupInterface[]
     */
    public function getAll(): array
    {
        return $this->groupList;
    }
}
