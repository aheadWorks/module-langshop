<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Magento\Cms\Model\ResourceModel\Page\Grid;

use Magento\Cms\Model\ResourceModel\AbstractCollection;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding\TranslationFilter;

class CollectionPlugin
{
    /**
     * @param TranslationFilter $translationFilter
     */
    public function __construct(
        private readonly TranslationFilter $translationFilter
    ) {
    }

    /**
     * Added custom filter
     *
     * @param AbstractCollection $subject
     * @param callable $proceed
     * @param $field
     * @param $condition
     * @return AbstractCollection
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundAddFieldToFilter(
        AbstractCollection $subject,
        callable $proceed,
        $field,
        $condition,
    ): AbstractCollection {
        if ($field == 'aw_ls_translation') {
            $this->translationFilter->apply($subject, ResourceBindingInterface::CMS_PAGE_RESOURCE_TYPE, 'page_id');
            return $subject;
        }

        return $proceed($field, $condition);
    }
}
