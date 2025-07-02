<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\AutoTranslation;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\Config\AutoUpdateTranslation;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding as BindingResourceModel;

class Checker
{
    /**
     * @param AutoUpdateTranslation $autoUpdateTranslation
     * @param LocaleScopeRepository $localeScopeRepository
     * @param BindingResourceModel $bindingResourceModel
     */
    public function __construct(
        private readonly AutoUpdateTranslation $autoUpdateTranslation,
        private readonly LocaleScopeRepository $localeScopeRepository,
        private readonly BindingResourceModel $bindingResourceModel
    ) {
    }

    /**
     * Can auto translate
     *
     * @param AbstractModel $resource
     * @param string $resourceType
     * @return bool
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function canAutoTranslate(AbstractModel $resource, string $resourceType): bool
    {
        if (!$this->autoUpdateTranslation->isEnabled()) {
            return false;
        }

        $defaultLocale = $this->localeScopeRepository->getPrimary($resourceType);
        if ($this->getStoreId($resource, $resourceType) != $defaultLocale->getScopeId()) {
            return false;
        }

        return true;
    }

    /**
     * Get store ID
     *
     * @param AbstractModel $resource
     * @param string $resourceType
     * @return int
     * @throws LocalizedException
     */
    private function getStoreId(AbstractModel $resource, string $resourceType): int
    {
        if (in_array(
            $resourceType,
            [
                ResourceBindingInterface::CMS_BLOCK_RESOURCE_TYPE,
                ResourceBindingInterface::CMS_PAGE_RESOURCE_TYPE,
                ResourceBindingInterface::AGREEMENT_RESOURCE_TYPE
            ]
        )) {
            $storeId = $this->bindingResourceModel->getResourceStoreId($resourceType, (int)$resource->getId());
            if ($storeId === null) {
                $storeId = Store::DEFAULT_STORE_ID;
            }
        } else {
            $storeId = (int)$resource->getData('store_id');
        }

        return $storeId;
    }
}
