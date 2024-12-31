<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Source\Entity\Binding;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class FrequencyLogCleaning
 */
class Translation implements OptionSourceInterface
{
    /**
     * Get options as array
     *
     * @return array
     */
    public function toOptionArray():  array
    {
        return [
            ['value' => 1, 'label' => __('Show Original Only')]
        ];
    }
}
