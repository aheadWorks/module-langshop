<?php
namespace Aheadworks\Langshop\Model\Source\Locale\Scope;

use Aheadworks\Langshop\Model\Source\AbstractOption as AbstractOptionSourceModel;

class Type extends AbstractOptionSourceModel
{
    /**#@+
     * Supported locale scope type values
     */
    const DEFAULT   = 'default';
    const WEBSITE   = 'website';
    const STORE     = 'store';
    /**#@-*/

    /**
     * Retrieve the list of locale scope types
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
