<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Binding\InitialSetup;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Store\Model\Store;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding as BindingResource;

class CmsBlock
{
    /**
     * @param BlockRepositoryInterface $blockRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param BindingResource $bindingResource
     */
    public function __construct(
        private readonly BlockRepositoryInterface $blockRepository,
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
        $searchResult = $this->blockRepository->getList($searchCriteria);
        $dataToInsert = [];
        foreach ($searchResult->getItems() as $block) {
            $dataToInsert[] = [
                ResourceBindingInterface::RESOURCE_TYPE => ResourceBindingInterface::CMS_BLOCK_RESOURCE_TYPE,
                ResourceBindingInterface::ORIGINAL_RESOURCE_ID => $block->getId(),
                ResourceBindingInterface::TRANSLATED_RESOURCE_ID => $block->getId(),
                ResourceBindingInterface::STORE_ID => Store::DEFAULT_STORE_ID
            ];
        }

        $this->bindingResource->massSave($dataToInsert);
    }
}
