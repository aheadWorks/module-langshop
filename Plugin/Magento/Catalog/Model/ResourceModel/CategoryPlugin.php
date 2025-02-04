<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Magento\Catalog\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Webapi\Exception;
use Magento\Catalog\Model\ResourceModel\Category as CategoryResourceModel;
use Aheadworks\Langshop\Model\Entity\AutoTranslation\Processor;

class CategoryPlugin
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
     * @param CategoryResourceModel $subject
     * @param CategoryResourceModel $result
     * @param AbstractModel $productResult
     * @return CategoryResourceModel
     * @throws Exception
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        CategoryResourceModel $subject,
        CategoryResourceModel $result,
        AbstractModel $productResult,
    ): CategoryResourceModel {
        $this->processor->forceTranslate($productResult, 'category');

        return $result;
    }
}
