<?php
namespace Aheadworks\Langshop\Model\ResourceModel\Locale\Scope\Record;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface as LocaleScopeRecordInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record as LocaleScopeRecord;
use Aheadworks\Langshop\Model\ResourceModel\AbstractCollection;
use Aheadworks\Langshop\Model\ResourceModel\Locale\Scope\Record as LocaleScopeRecordResourceModel;

class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected $_idFieldName = LocaleScopeRecordInterface::RECORD_ID;

    /**
     * Resource collection initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            LocaleScopeRecord::class,
            LocaleScopeRecordResourceModel::class
        );
    }
}
