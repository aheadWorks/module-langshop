<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Magento\Cms\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Cms\Model\ResourceModel\Page as PageResourceModel;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\Entity\Binding\Manager as BindingManager;
use Aheadworks\Langshop\Model\Entity\AutoTranslation\Processor;

class PagePlugin
{
    /**
     * @param BindingManager $bindingManager
     * @param Processor $processor
     */
    public function __construct(
        private readonly BindingManager $bindingManager,
        private readonly Processor $processor
    ) {
    }

    /**
     * Bind page resource type
     *
     * @param PageResourceModel $subject
     * @param PageResourceModel $result
     * @param AbstractModel $pageResult
     * @return PageResourceModel
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        PageResourceModel $subject,
        PageResourceModel $result,
        AbstractModel $pageResult,
    ): PageResourceModel {
        $this->bindingManager->processEntityBinding($pageResult, ResourceBindingInterface::CMS_PAGE_RESOURCE_TYPE);
        $this->processor->forceTranslate($pageResult, ResourceBindingInterface::CMS_PAGE_RESOURCE_TYPE);
        return $result;
    }

    /**
     * Remove binding
     *
     * @param PageResourceModel $subject
     * @param PageResourceModel $result
     * @param AbstractModel $page
     * @return PageResourceModel
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDelete(
        PageResourceModel $subject,
        PageResourceModel $result,
        AbstractModel $page,
    ): PageResourceModel {
        $this->bindingManager->removeBinding(ResourceBindingInterface::CMS_PAGE_RESOURCE_TYPE, (int)$page->getId());
        return $result;
    }
}
