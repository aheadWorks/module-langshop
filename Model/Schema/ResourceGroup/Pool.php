<?php
namespace Aheadworks\Langshop\Model\Schema\ResourceGroup;

use Aheadworks\Langshop\Api\Data\Schema\ResourceGroupInterface;

class Pool
{
    /**
     * @var array
     */
    private $groupList;

    /**
     * @param array $groupList
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
    public function getAll()
    {
        return $this->groupList;
    }
}
