<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Field;

use Magento\Framework\Model\AbstractModel;

interface ProcessorInterface
{
    /**
     * Retrieves data for the particular field/store
     *
     * @param AbstractModel[] $items
     * @param int $storeId
     * @return void
     */
    public function load(array $items, int $storeId): void;
}
