<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Status extends AbstractDb
{
    public const TABLE_NAME = 'aw_ls_status';

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'status_id');
    }

    /**
     * Mass save
     *
     * @param array $data
     * @return $this
     * @throws LocalizedException
     */
    public function massSave(array $data): Status
    {
        $this->getConnection()->insertOnDuplicate(
            $this->getMainTable(),
            $data
        );

        return $this;
    }
}
