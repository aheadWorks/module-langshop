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
     * @param int $resourceId
     * @return bool
     */
    public function isTranslated(string $resourceType, int $resourceId): bool
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(StatusInterface::RESOURCE_TYPE, $resourceType)
            ->addFilter(StatusInterface::RESOURCE_ID, $resourceId)
            ->addFilter(StatusInterface::STATUS, StatusInterface::STATUS_TRANSLATED)
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
