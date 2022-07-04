<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection;

use Aheadworks\Langshop\Model\Csv\Model;
use Magento\Framework\Data\Collection;

class SortingApplier
{
    /**
     * @var string
     */
    private string $field;

    /**
     * Apply sorting
     *
     * @param array $items
     * @param array $orders
     * @return void
     */
    public function apply(array &$items, array $orders): void
    {
        $cmpFunction = function (Model $firstModel, Model $secondModel): int {
            $firstValue = $firstModel->getData($this->field);
            $secondValue = $secondModel->getData($this->field);
            return strnatcmp($firstValue, $secondValue);
        };

        foreach ($orders as $field => $direction) {
            $this->field = $field;
            uasort($items, $cmpFunction);
            if ($direction === Collection::SORT_ORDER_DESC) {
                $items = array_reverse($items);
            }
        }
    }
}
