<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource;

use Exception;
use Magento\Catalog\Model\ResourceModel\Category as CategoryResourceModel;
use Magento\Catalog\Model\ResourceModel\Category\Proxy as CategoryResourceModelProxy;
use Magento\Framework\Model\AbstractModel;

class Category extends CategoryResourceModelProxy
{
    /**
     * Save object data
     *
     * @param AbstractModel $object
     * @return $this
     * @throws Exception
     */
    public function save(AbstractModel $object): CategoryResourceModel
    {
        // @see \Magento\CatalogUrlRewrite\Observer\CategoryUrlPathAutogeneratorObserver
        $object->setData('use_default', [
            'url_key' => true
        ]);

        return parent::save($object);
    }
}
