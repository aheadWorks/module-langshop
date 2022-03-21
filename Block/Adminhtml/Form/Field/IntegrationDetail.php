<?php
namespace Aheadworks\Langshop\Block\Adminhtml\Form\Field;

use Aheadworks\Langshop\Model\Service\Integration as IntegrationService;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field as BaseField;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class IntegrationDetail extends BaseField
{
    /**
     * @var IntegrationService
     */
    private $integrationService;

    /**
     * @param Context $context
     * @param IntegrationService $integrationService
     * @param array $data
     * @param SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        Context $context,
        IntegrationService $integrationService,
        array $data = [],
        SecureHtmlRenderer $secureRenderer = null
    ) {
        parent::__construct($context, $data, $secureRenderer);
        $this->integrationService = $integrationService;
    }

    /**
     * Render element value
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _renderValue(AbstractElement $element)
    {
        $id = $element->getOriginalData('id');
        try {
            $integration = $this->integrationService->getIntegration();
            $value = $integration->getData($id);
        } catch (IntegrationException $exception) {
            $value = '';
        }

        return '<td class="value">' . $value . '</td>';
    }
}
