<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CatalogCollectionTrait;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

class Collection extends ProductCollection
{
    use CatalogCollectionTrait;
}
