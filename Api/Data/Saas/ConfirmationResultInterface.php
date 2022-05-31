<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\Saas;

interface ConfirmationResultInterface
{
    public const SUCCESS = 'success';

    /**
     * Set result of confirm action
     *
     * @param bool $success
     * @return \Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface
     */
    public function setSuccess(bool $success): ConfirmationResultInterface;

    /**
     * Get result of confirm action
     *
     * @return bool
     */
    public function isSuccess():bool;
}
