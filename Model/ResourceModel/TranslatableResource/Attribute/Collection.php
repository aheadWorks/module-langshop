<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionInterface;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionTrait;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection as AttributeCollection;

class Collection extends AttributeCollection implements CollectionInterface
{
    use CollectionTrait;
}
