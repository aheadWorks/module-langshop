<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Block\Adminhtml\Button\Attribute;

use Aheadworks\Langshop\Block\Adminhtml\Button\Translate as TranslateButton;
use Magento\Backend\Block\Widget\Container;
use Magento\Backend\Block\Widget\Context;

class Translate extends Container
{
    /**
     * @var TranslateButton
     */
    private TranslateButton $translateButton;

    /**
     * @param TranslateButton $translateButton
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        TranslateButton $translateButton,
        Context $context,
        array $data = []
    ) {
        $this->translateButton = $translateButton;
        parent::__construct($context, $data);
    }

    /**
     * Adds new translate button to the layout
     */
    protected function _construct()
    {
        $button = $this->translateButton->getButtonData();
        if ($button) {
            $this->addButton('translate', $button, -1, 10);
        }

        parent::_construct();
    }
}
