<?php
namespace Aheadworks\Langshop\Model\ResourceModel\Locale\ScopeRecord;

use Aheadworks\Langshop\Model\ResourceModel\AbstractCollection;
use Aheadworks\Langshop\Model\ResourceModel\Locale\ScopeRecord
    as LocaleScopeRecordResourceModel;
use Aheadworks\Langshop\Model\Locale\ScopeRecordInterface
    as LocaleScopeRecordInterface;
use Aheadworks\Langshop\Model\Locale\ScopeRecord
    as LocaleScopeRecord;

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
