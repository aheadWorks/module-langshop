<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Block\Adminhtml\Button;

use Aheadworks\Langshop\Model\Saas\ModuleChecker;
use Aheadworks\Langshop\Model\Status\StatusChecker;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Translate implements ButtonProviderInterface
{
    /**
     * Url for ajax requests
     */
    private const TRANSLATE_URL = 'langshop/saas/translate';

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @var ModuleChecker
     */
    private ModuleChecker $moduleChecker;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var string
     */
    private string $resourceType;

    /**
     * @var string
     */
    private string $resourceIdParam;

    /**
     * @var StatusChecker
     */
    private StatusChecker $statusChecker;

    /**
     * @param UrlInterface $urlBuilder
     * @param ModuleChecker $moduleChecker
     * @param RequestInterface $request
     * @param StatusChecker $statusChecker
     * @param string $resourceType
     * @param string $resourceIdParam
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ModuleChecker $moduleChecker,
        RequestInterface $request,
        StatusChecker $statusChecker,
        string $resourceType,
        string $resourceIdParam = 'id'
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->moduleChecker = $moduleChecker;
        $this->request = $request;
        $this->statusChecker = $statusChecker;
        $this->resourceType = $resourceType;
        $this->resourceIdParam = $resourceIdParam;
    }

    /**
     * Retrieves button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        if ($this->moduleChecker->isConfigured() && $this->getResourceId()) {
            return [
                'label' => __('Translate'),
                'data_attribute' => [
                    'mage-init' => [
                        'Aheadworks_Langshop/js/translate-button' => [
                            'isDisabled' => $this->statusChecker->isProcessing(
                                $this->resourceType,
                                (string)$this->getResourceId()
                            ),
                            'successMessage' => __('Processing %1 translation.', $this->resourceType),
                            'translateUrl' => $this->urlBuilder->getUrl(self::TRANSLATE_URL),
                            'ajaxParams' => [
                                'resource_type' => $this->resourceType,
                                'resource_id' => $this->getResourceId()
                            ]
                        ]
                    ]
                ],
                'on_click' => ''
            ];
        }

        return [];
    }

    /**
     * Retrieves identifier for the current resource
     *
     * @return int
     */
    private function getResourceId(): int
    {
        return (int) $this->request->getParam($this->resourceIdParam);
    }
}
