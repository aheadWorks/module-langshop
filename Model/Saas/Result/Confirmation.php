<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas\Result;

use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface;
use Magento\Framework\DataObject;

class Confirmation extends DataObject implements ConfirmationResultInterface
{
    /**
     * Constants for internal keys
     */
    private const SUCCESS = 'success';

    /**
     * Set result of confirm action
     *
     * @param bool $success
     * @return \Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface
     */
    public function setSuccess(bool $success): ConfirmationResultInterface
    {
        return $this->setData(self::SUCCESS, $success);
    }

    /**
     * Get result of confirm action
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getData(self::SUCCESS);
    }
}
