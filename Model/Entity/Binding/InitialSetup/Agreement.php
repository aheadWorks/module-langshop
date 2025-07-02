<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Binding\InitialSetup;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\Store\Model\Store;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding as BindingResource;

class Agreement
{
    /**
     * @param CheckoutAgreementsListInterface $agreementsRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param BindingResource $bindingResource
     */
    public function __construct(
        private readonly CheckoutAgreementsListInterface $agreementsRepository,
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
        $agreements = $this->agreementsRepository->getList($searchCriteria);
        $dataToInsert = [];
        foreach ($agreements as $agreement) {
            $dataToInsert[] = [
                ResourceBindingInterface::RESOURCE_TYPE => ResourceBindingInterface::AGREEMENT_RESOURCE_TYPE,
                ResourceBindingInterface::ORIGINAL_RESOURCE_ID => $agreement->getId(),
                ResourceBindingInterface::TRANSLATED_RESOURCE_ID => $agreement->getId(),
                ResourceBindingInterface::STORE_ID => Store::DEFAULT_STORE_ID
            ];
        }

        $this->bindingResource->massSave($dataToInsert);
    }
}
