<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Magento\Cms\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Cms\Model\ResourceModel\Block as BlockResourceModel;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\Entity\Binding\Manager as BindingManager;
use Aheadworks\Langshop\Model\Entity\AutoTranslation\Processor;

class BlockPlugin
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
     * Bind block resource type
     *
     * @param BlockResourceModel $subject
     * @param BlockResourceModel $result
     * @param AbstractModel $blockResult
     * @return BlockResourceModel
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        BlockResourceModel $subject,
        BlockResourceModel $result,
        AbstractModel $blockResult,
    ): BlockResourceModel {
        $this->bindingManager->processEntityBinding($blockResult, ResourceBindingInterface::CMS_BLOCK_RESOURCE_TYPE);
        $this->processor->forceTranslate($blockResult, ResourceBindingInterface::CMS_BLOCK_RESOURCE_TYPE);
        return $result;
    }

    /**
     * Remove binding
     *
     * @param BlockResourceModel $subject
     * @param BlockResourceModel $result
     * @param AbstractModel $block
     * @return BlockResourceModel
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDelete(
        BlockResourceModel $subject,
        BlockResourceModel $result,
        AbstractModel $block,
    ): BlockResourceModel {
        $this->bindingManager->removeBinding(ResourceBindingInterface::CMS_BLOCK_RESOURCE_TYPE, (int)$block->getId());
        return $result;
    }
}
