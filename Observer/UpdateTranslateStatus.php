<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Observer;

use Aheadworks\Langshop\Api\Data\StatusInterface;
use Aheadworks\Langshop\Api\Data\StatusInterfaceFactory;
use Aheadworks\Langshop\Api\StatusManagementInterface;
use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class UpdateTranslateStatus implements ObserverInterface
{
    /**
     * @var StatusManagementInterface
     */
    private StatusManagementInterface $statusManagement;

    /**
     * @var StatusInterfaceFactory
     */
    private StatusInterfaceFactory $statusFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @param StatusManagementInterface $statusManagement
     * @param StatusInterfaceFactory $statusFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        StatusManagementInterface $statusManagement,
        StatusInterfaceFactory $statusFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->statusFactory = $statusFactory;
        $this->statusManagement = $statusManagement;
    }

    /**
     * Updates the status of the resource after saving
     *
     * @param Observer $observer
     * @throws Exception
     */
    public function execute(Observer $observer): void
    {
        $resourceType = (string) $observer->getData('resource_type');
        $resourceId = (int) $observer->getData('resource_id');
        $storeId = (int) $observer->getData('store_id');

        if ($resourceType && $resourceId && $storeId) {
            $status = $this->findStatus($resourceType, $resourceId, $storeId);

            if ($status) {
                $this->statusManagement->save($status->setStatus(true));
            } else {
                $status = $this->statusFactory->create()
                    ->setResourceType($resourceType)
                    ->setResourceId($resourceId)
                    ->setStoreId($storeId)
                    ->setStatus(true);

                $this->statusManagement->save($status);
            }
        }
    }

    /**
     * Finds the status with required criteria
     *
     * @param string $resourceType
     * @param int $resourceId
     * @param int $storeId
     * @return StatusInterface|null
     */
    private function findStatus(
        string $resourceType,
        int $resourceId,
        int $storeId
    ): ?StatusInterface {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('resource_type', $resourceType)
            ->addFilter('resource_id', $resourceId)
            ->addFilter('store_id', $storeId)
            ->create();

        $statuses = $this->statusManagement->getList($searchCriteria);
        if ($statuses) {
            return current($statuses);
        }

        return null;
    }
}
