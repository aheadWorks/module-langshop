<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\Status;

use Aheadworks\Langshop\Model\ResourceModel\Status as StatusResource;
use Aheadworks\Langshop\Model\Status;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Relation to the model/resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Status::class, StatusResource::class);
    }
}
