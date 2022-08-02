<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Block\Adminhtml\Form\Field;

use Aheadworks\Langshop\Model\Service\Integration as IntegrationService;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field as BaseField;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\IntegrationException;

class IntegrationDetail extends BaseField
{
    /**
     * @var IntegrationService
     */
    private IntegrationService $integrationService;

    /**
     * @param Context $context
     * @param IntegrationService $integrationService
     * @param array $data
     */
    public function __construct(
        Context $context,
        IntegrationService $integrationService,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->integrationService = $integrationService;
    }

    /**
     * Retrieves element HTML markup
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $id = $element->getDataByPath('original_data/id');
        try {
            $integration = $this->integrationService->getIntegration();
            $value = $integration->getData($id);
        } catch (IntegrationException $exception) {
            $value = '';
        }

        return $value;
    }
}
