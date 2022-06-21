<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Category;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CatalogCollectionTrait;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;

class Collection extends CategoryCollection
{
    use CatalogCollectionTrait;
}
