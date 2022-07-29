<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Api;

use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface;

interface NotificationManagementInterface
{
    /**
     * Create new notification
     *
     * @param string $resourceType
     * @param string $resourceId
     * @param int $status
     * @param string $errorMessage
     * @return \Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface
     */
    public function create(
        string $resourceType,
        string $resourceId,
        int $status,
        string $errorMessage = ''
    ): ConfirmationResultInterface;
}
