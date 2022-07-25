<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Status;

use Aheadworks\Langshop\Api\Data\StatusInterface;
use Aheadworks\Langshop\Api\StatusManagementInterface;
use Aheadworks\Langshop\Model\Config\ListToTranslate as ListToTranslateConfig;
use Magento\Framework\Api\SearchCriteriaBuilder;

class StatusChecker
{
    /**
     * @var StatusManagementInterface
     */
    private StatusManagementInterface $statusManagement;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var ListToTranslateConfig
     */
    private ListToTranslateConfig $listToTranslateConfig;

    /**
     * @param StatusManagementInterface $statusManagement
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ListToTranslateConfig $listToTranslateConfig
     */
    public function __construct(
        StatusManagementInterface $statusManagement,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ListToTranslateConfig $listToTranslateConfig
    ) {
        $this->statusManagement = $statusManagement;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->listToTranslateConfig = $listToTranslateConfig;
    }

    /**
     * Checks if the particular resource is fully translated
     *
     * @param string $resourceType
     * @param string $resourceId
     * @return bool
     */
    public function isTranslated(string $resourceType, string $resourceId): bool
    {
        return $this->checkResourceStatus($resourceType, $resourceId, StatusInterface::STATUS_TRANSLATED);
    }

    /**
     * Checks if the particular resource is fully processing
     *
     * @param string $resourceType
     * @param string $resourceId
     * @return bool
     */
    public function isProcessing(string $resourceType, string $resourceId): bool
    {
        return $this->checkResourceStatus($resourceType, $resourceId, StatusInterface::STATUS_PROCESSING);
    }

    /**
     * Checks the status of a specific resource
     *
     * @param string $resourceType
     * @param string $resourceId
     * @param int $status
     * @return bool
     */
    private function checkResourceStatus(string $resourceType, string $resourceId, int $status): bool
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(StatusInterface::RESOURCE_TYPE, $resourceType)
            ->addFilter(StatusInterface::RESOURCE_ID, $resourceId)
            ->addFilter(StatusInterface::STATUS, $status)
            ->create();

        $translatedStores = array_map(
            fn (StatusInterface $status) => $status->getStoreId(),
            $this->statusManagement->getList($searchCriteria)
        );

        return !array_diff(
            $this->listToTranslateConfig->getValue(),
            $translatedStores
        );
    }
}