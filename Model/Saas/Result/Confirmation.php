<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas\Result;

use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface;
use Magento\Framework\DataObject;

class Confirmation extends DataObject implements ConfirmationResultInterface
{
    /**
     * @inheritDoc
     */
    public function setSuccess(bool $success): ConfirmationResultInterface
    {
        return $this->setData(self::SUCCESS, $success);
    }

    /**
     * @inheritDoc
     */
    public function isSuccess(): bool
    {
        return $this->getData(self::SUCCESS);
    }
}