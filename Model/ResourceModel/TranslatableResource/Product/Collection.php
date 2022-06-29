<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionInterface;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionTrait;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

class Collection extends ProductCollection implements CollectionInterface
{
    use CollectionTrait;
}
