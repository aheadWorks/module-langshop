<?php
declare(strict_types = 1);
namespace Aheadworks\Langshop\ViewModel\Page;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class Iframe implements ArgumentInterface
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
