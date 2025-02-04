<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Magento\CheckoutAgreements\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\CheckoutAgreements\Model\ResourceModel\Agreement as AgreementResourceModel;
use Magento\CheckoutAgreements\Model\Agreement;
use Magento\CheckoutAgreements\Model\AgreementFactory;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\Entity\Binding\Manager as BindingManager;
use Aheadworks\Langshop\Model\Entity\AutoTranslation\Processor;

class AgreementPlugin
{
    /**
     * @param BindingManager $bindingManager
     * @param Processor $processor
     * @param AgreementFactory $agreementFactory
     */
    public function __construct(
        private readonly BindingManager $bindingManager,
        private readonly Processor $processor,
        private readonly AgreementFactory $agreementFactory
    ) {
    }

    /**
     * Bind agreement resource type
     *
     * @param AgreementResourceModel $subject
     * @param callable $proceed
     * @param AbstractModel $agreement
     * @return AgreementResourceModel
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function aroundSave(
        AgreementResourceModel $subject,
        callable $proceed,
        AbstractModel $agreement,
    ): AgreementResourceModel {
        /** @var Agreement $originalModel */
        $originalAgreement = $this->agreementFactory->create();
        if ($agreement->getId()) {
            $subject->load($originalAgreement, $agreement->getId());
        }

        $result = $proceed($agreement);
        $this->bindingManager->processEntityBinding($agreement, ResourceBindingInterface::AGREEMENT_RESOURCE_TYPE);
        foreach ($originalAgreement->getData() as $key => $value) {
            $agreement->setOrigData($key, $value);
        }

        $this->processor->forceTranslate($agreement, ResourceBindingInterface::AGREEMENT_RESOURCE_TYPE);

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
