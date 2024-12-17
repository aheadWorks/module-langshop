<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Magento\Backend\Model\Menu;

use Magento\Backend\Model\Menu\Builder;
use Magento\Backend\Model\Menu;
use Aheadworks\Langshop\Model\Mode\State as ModeState;

class BuilderPlugin
{
    /**
     * @param ModeState $modeState
     */
    public function __construct(
        private readonly ModeState $modeState
    ) {
    }

    /**
     * @param Builder $subject
     * @param Menu $menu
     * @return Menu
     */
    public function afterGetResult(Builder $subject, Menu $menu): Menu
    {
        if ($this->modeState->isAppBuilderMode()) {
            $menu->remove('Aheadworks_Langshop::langshop');
        }

        return $menu;
    }
}
