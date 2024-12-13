<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Mode;

use Magento\Framework\Flag as FrameworkFlag;

class Flag extends FrameworkFlag
{
    /**
     * Flag code
     *
     * @var string
     */
    protected $_flagCode = 'aw_ls_rendering_mode';
}
