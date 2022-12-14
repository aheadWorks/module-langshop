<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Source\Locale\Scope;

use Aheadworks\Langshop\Model\Source\AbstractOption as AbstractOptionSourceModel;

class Type extends AbstractOptionSourceModel
{
    /**#@+
     * Supported locale scope type values
     */
    public const DEFAULT   = 'default';
    public const WEBSITE   = 'website';
    public const STORE     = 'store';
    /**#@-*/

    /**
     * Retrieve the list of locale scope types
     *
     * @return array
     */
    protected function getOptionList(): array
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
