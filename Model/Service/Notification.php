<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface;
use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterfaceFactory;
use Aheadworks\Langshop\Api\Data\StatusInterface;
use Aheadworks\Langshop\Api\NotificationManagementInterface;
use Aheadworks\Langshop\Api\StatusManagementInterface;
use Magento\AdminNotification\Model\Inbox;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Notification implements NotificationManagementInterface
{
    /**
     * @var ConfirmationResultInterfaceFactory
     */
    private ConfirmationResultInterfaceFactory $resultFactory;

    /**
     * @var Inbox
     */
    private Inbox $notificationService;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var StatusManagementInterface
     */
    private StatusManagementInterface $statusManager;

    /**
     * @param ConfirmationResultInterfaceFactory $resultFactory
     * @param Inbox $notificationService
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param StatusManagementInterface $statusManager
     */
    public function __construct(
        ConfirmationResultInterfaceFactory $resultFactory,
        Inbox $notificationService,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StatusManagementInterface $statusManager
    ) {
        $this->resultFactory = $resultFactory;
        $this->notificationService = $notificationService;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->statusManager = $statusManager;
    }

    /**
     * Create admin inbox notification
     *
     * @param string $resourceType
     * @param string $resourceId
     * @param int $status
     * @param string $errorMessage
     * @return ConfirmationResultInterface
     */
    public function create(
        string $resourceType,
        string $resourceId,
        int $status,
        string $errorMessage = ''
    ): ConfirmationResultInterface {
        $isSuccess = false;
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(StatusInterface::RESOURCE_ID, $resourceId)
            ->addFilter(StatusInterface::RESOURCE_TYPE, $resourceType)
            ->create();
        $statuses = $this->statusManager->getList($searchCriteria);

        if ($status === 1) {
            $this->notificationService->addNotice(
                __("Translation successfully completed")->render(),
                __(
                    "Translation of %1 with identifier = '%2' successfully completed.",
                    $resourceType,
                    $resourceId
                )->render()
            );
            $isSuccess = true;
        } else {
            $this->notificationService->addNotice(
                __("Translation failed")->render(),
                $errorMessage
            );
        }

        foreach ($statuses as $status) {
            $status->setStatus((int)$isSuccess);
        }
        $this->statusManager->massSave($statuses);

        /** @var ConfirmationResultInterface $result */
        $result = $this->resultFactory->create();
        return $result->setSuccess($isSuccess);
    }
}
