<?php
namespace Aheadworks\Langshop\Model\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

interface OperationInterface
{
    /**
     * Perform operation over entity data array
     *
     * @param array $entityData
     * @return AbstractModel
     * @throws LocalizedException
     */
    public function execute($entityData);
}
