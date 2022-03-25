<?php
namespace Aheadworks\Langshop\Model\ResourceModel\Locale\Scope;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface as LocaleScopeRecordInterface;
use Aheadworks\Langshop\Model\ResourceModel\AbstractResourceModel;

class Record extends AbstractResourceModel
{
    /**#@+
     * Constants defined for tables
     * used by corresponding entity
     */
    const MAIN_TABLE_NAME = 'aw_ls_locale_scope';
    /**#@-*/

    /**
     * Resource model initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            self::MAIN_TABLE_NAME,
            LocaleScopeRecordInterface::RECORD_ID
        );
    }
}
