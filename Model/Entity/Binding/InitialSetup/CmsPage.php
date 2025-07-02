<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Binding\InitialSetup;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Store\Model\Store;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding as BindingResource;

class CmsPage
{
    /**
     * @param PageRepositoryInterface $pageRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param BindingResource $bindingResource
     */
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly BindingResource $bindingResource
    ) {
    }

    /**
     * Install initial data
     *
     * @throws LocalizedException
     */
    public function installInitialData(): void
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResult = $this->pageRepository->getList($searchCriteria);
        $dataToInsert = [];
        foreach ($searchResult->getItems() as $page) {
            $dataToInsert[] = [
                ResourceBindingInterface::RESOURCE_TYPE => ResourceBindingInterface::CMS_PAGE_RESOURCE_TYPE,
                ResourceBindingInterface::ORIGINAL_RESOURCE_ID => $page->getId(),
                ResourceBindingInterface::TRANSLATED_RESOURCE_ID => $page->getId(),
                ResourceBindingInterface::STORE_ID => Store::DEFAULT_STORE_ID
            ];
        }

        $this->bindingResource->massSave($dataToInsert);
    }
}
