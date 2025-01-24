<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Magento\CheckoutAgreements\Block\Adminhtml\Agreement;

use Magento\Framework\View\LayoutInterface;
use Magento\Backend\Block\Widget\Form\Container;
use Aheadworks\Langshop\Block\Adminhtml\Button\BindingResource\Translate as TranslateButton;

class EditPlugin
{
    /**
     * @param TranslateButton $translateButton
     */
    public function __construct(
        private readonly TranslateButton $translateButton
    ) {
    }

    /**
     * Add Adobe Stock Search button to the toolbar
     *
     * @param Container $subject
     * @param LayoutInterface $layout
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSetLayout(Container $subject, LayoutInterface $layout): void
    {
        $buttonData = $this->translateButton->getButtonData();
        if ($buttonData) {
            $subject->addButton(
                'aw_ls_translate_agreement',
                $this->translateButton->getButtonData(),
                -1
            );
        }
    }
}
