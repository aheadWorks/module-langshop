<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Block\Adminhtml\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement as FormAbstractElement;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field as BaseField;
use Aheadworks\Langshop\Model\Mode\State as ModeState;

class HideDependOnModeField extends BaseField
{
    /**
     * @param Context $context
     * @param ModeState $modeState
     * @param array $data
     */
    public function __construct(
        Context $context,
        private readonly ModeState $modeState,
        array $data = []
    ) {
        parent::__construct($context, $data);
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
