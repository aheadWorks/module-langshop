<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Category;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionInterface;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionTrait;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\EavCollectionTrait;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;

class Collection extends CategoryCollection implements CollectionInterface
{
    use CollectionTrait;
    use EavCollectionTrait;
}
