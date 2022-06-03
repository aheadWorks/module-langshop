<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource;

use Exception;
use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;
use Magento\Catalog\Model\ResourceModel\Product\Proxy as ProductResourceModelProxy;
use Magento\Framework\Model\AbstractModel;

class Product extends ProductResourceModelProxy
{
    /**
     * Save object data
     *
     * @param AbstractModel $object
     * @return ProductResourceModel
     * @throws Exception
     */
    public function save(AbstractModel $object): ProductResourceModel
    {
        $object->setData('use_default', [
            'url_key' => true
        ]);

        return parent::save($object);
    }
}
