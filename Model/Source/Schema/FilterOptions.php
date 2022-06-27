<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Source\Schema;

use Magento\Framework\Data\OptionSourceInterface;

class FilterOptions implements OptionSourceInterface
{
    /**
     * @var array
     */
    private array $options;

    /**
     * @param array $options
     */
    public function __construct(
        array $options = []
    ) {
        $this->options = $options;
    }

    /**
     * Retrieves compiled options
     *
     * @return array
     */
    public function toOptionArray()
    {
        ksort($this->options);

        return $this->options;
    }
}
