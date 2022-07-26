<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Api;

interface NotificationManagementInterface
{
    /**
     * Create new notification
     *
     * @param string $resourceType
     * @param string $resourceId
     * @return bool
     */
    public function create(string $resourceType, string $resourceId): bool;
}
