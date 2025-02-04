<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Magento\Catalog\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Webapi\Exception;
use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;
use Aheadworks\Langshop\Model\Entity\AutoTranslation\Processor;

class ProductPlugin
{
    /**
     * @param Processor $processor
     */
    public function __construct(
        private readonly Processor $processor
    ) {
    }

    /**
     * Process entity after save
     *
     * @param ProductResourceModel $subject
     * @param ProductResourceModel $result
     * @param AbstractModel $productResult
     * @return ProductResourceModel
     * @throws Exception
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        ProductResourceModel $subject,
        ProductResourceModel $result,
        AbstractModel $productResult,
    ): ProductResourceModel {
        $this->processor->forceTranslate($productResult, 'product');

        return $result;
    }
}
