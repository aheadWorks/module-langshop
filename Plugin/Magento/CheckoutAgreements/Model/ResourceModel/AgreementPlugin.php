<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Magento\CheckoutAgreements\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\CheckoutAgreements\Model\ResourceModel\Agreement as AgreementResourceModel;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\Entity\Binding\Manager as BindingManager;

class AgreementPlugin
{
    /**
     * @param BindingManager $bindingManager
     */
    public function __construct(
        private readonly BindingManager $bindingManager
    ) {
    }

    /**
     * Bind agreement resource type
     *
     * @param AgreementResourceModel $subject
     * @param AgreementResourceModel $result
     * @param AbstractModel $blockResult
     * @return AgreementResourceModel
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        AgreementResourceModel $subject,
        AgreementResourceModel $result,
        AbstractModel $agreementResult,
    ): AgreementResourceModel {
        $this->bindingManager->processEntityBinding($agreementResult, ResourceBindingInterface::AGREEMENT_RESOURCE_TYPE);
        return $result;
    }

    /**
     * Remove binding
     *
     * @param AgreementResourceModel $subject
     * @param AgreementResourceModel $result
     * @param AbstractModel $agreement
     * @return AgreementResourceModel
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDelete(
        AgreementResourceModel $subject,
        AgreementResourceModel $result,
        AbstractModel $agreement,
    ): AgreementResourceModel {
        $this->bindingManager->removeBinding(ResourceBindingInterface::AGREEMENT_RESOURCE_TYPE, (int)$agreement->getId());
        return $result;
    }
}
