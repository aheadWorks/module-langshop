<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

abstract class AbstractOption implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected array $optionList = [];

    /**
     * Get option list
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (empty($this->optionList)) {
            $this->optionList = $this->getOptionList();
        }

        return $this->optionList;
    }

    /**
     * Retrieve the list of options
     *
     * @return array
     */
    abstract protected function getOptionList(): array;
}
