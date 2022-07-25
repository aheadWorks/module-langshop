<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\NotificationManagementInterface;
use Aheadworks\Langshop\Model\Status\StatusChecker;
use Magento\AdminNotification\Model\Inbox;

class Notification implements NotificationManagementInterface
{
    /**
     * @var StatusChecker
     */
    private StatusChecker $statusChecker;

    /**
     * @var Inbox
     */
    private Inbox $notificationService;

    /**
     * @param StatusChecker $statusChecker
     * @param Inbox $notificationService
     */
    public function __construct(
        StatusChecker $statusChecker,
        Inbox $notificationService
    ) {
        $this->statusChecker = $statusChecker;
        $this->notificationService = $notificationService;
    }

    /**
     * Create admin inbox notification
     *
     * @param string $resourceType
     * @param string $resourceId
     * @return bool
     */
    public function create(string $resourceType, string $resourceId): bool
    {
        $result = false;
        if ($this->statusChecker->isTranslated($resourceType, $resourceId)) {
            $this->notificationService->addNotice(
                __("Translation successfully completed"),
                __("Translation of %1 with identifier = '%2' successfully completed.", $resourceType, $resourceId)
            );
            $result = true;
        }

        return $result;
    }
}
