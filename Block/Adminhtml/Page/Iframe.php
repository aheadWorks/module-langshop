<?php
declare(strict_types = 1);
namespace Aheadworks\Langshop\Block\Adminhtml\Page;

use Magento\Backend\Block\Template;

class Iframe extends Template
{
    /**
     * Get source url
     *
     * @return string
     */
    public function getSourceUrl(): string
    {
        return 'https://langshop.io/';
    }
}
