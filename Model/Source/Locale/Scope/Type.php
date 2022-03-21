<?php
namespace Aheadworks\Langshop\Model\Source\Locale\Scope;

use Magento\Framework\Data\OptionSourceInterface;

class Type implements OptionSourceInterface
{
    /**#@+
     * Supported locale scope type values
     */
    const DEFAULT   = 'default';
    const WEBSITE   = 'website';
    const STORE     = 'store';
    /**#@-*/

    /**
     * @var array|null
     */
    private $optionList;

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
    protected function getOptionList()
    {
        return [
            [
                'value' => self::DEFAULT,
                'label' => __('Default')
            ],
            [
                'value' => self::WEBSITE,
                'label' => __('Website')
            ],
            [
                'value' => self::STORE,
                'label' => __('Store View')
            ],
        ];
    }
}
