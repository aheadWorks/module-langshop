<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Helper\Js;
use Magento\Framework\Data\Form\Element\AbstractElement as FormAbstractElement;
use Magento\Backend\Model\Auth\Session;
use Magento\Backend\Block\Context;
use Magento\Config\Block\System\Config\Form\Fieldset as BaseFieldset;
use Aheadworks\Langshop\Model\Mode\State as ModeState;

class HideDependOnModeFieldset extends BaseFieldset
{
    /**
     * @param Context $context
     * @param Session $authSession
     * @param Js $jsHelper
     * @param ModeState $modeState
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $authSession,
        Js $jsHelper,
        private readonly ModeState $modeState,
        array $data = [],
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);
    }

    /**
     * Retrieve HTML markup for given form element
     *
     * @param FormAbstractElement $element
     * @return string
     */
    public function render(FormAbstractElement $element): string
    {
        $fieldHtml = '';

        if (!$this->modeState->isAppBuilderMode()) {
            $fieldHtml = parent::render($element);
        }

        return $fieldHtml;
    }
}
