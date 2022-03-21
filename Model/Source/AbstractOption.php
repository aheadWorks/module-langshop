<?php
namespace Aheadworks\Langshop\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

abstract class AbstractOption implements OptionSourceInterface
{
    /**
     * @var array|null
     */
    protected $optionList;

    /**
     * Get option list
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->optionList !== null) {
            return $this->optionList;
        }

        $this->optionList = $this->getOptionList();

        return $this->optionList;
    }

    /**
     * Retrieve the list of options
     *
     * @return array
     */
    abstract protected function getOptionList();
}
