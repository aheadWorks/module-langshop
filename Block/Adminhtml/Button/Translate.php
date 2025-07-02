<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Block\Adminhtml\Button;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Aheadworks\Langshop\Model\Saas\ModuleChecker;
use Aheadworks\Langshop\Model\Status\StatusChecker;

class Translate implements ButtonProviderInterface
{
    /**
     * Url for ajax requests
     */
    protected const TRANSLATE_URL = 'langshop/saas/translate';

    /**
     * @param UrlInterface $urlBuilder
     * @param ModuleChecker $moduleChecker
     * @param RequestInterface $request
     * @param StatusChecker $statusChecker
     * @param string $resourceType
     * @param string $resourceIdParam
     */
    public function __construct(
        protected readonly UrlInterface $urlBuilder,
        protected readonly ModuleChecker $moduleChecker,
        protected readonly RequestInterface $request,
        protected readonly StatusChecker $statusChecker,
        protected readonly string $resourceType,
        protected readonly string $resourceIdParam = 'id'
    ) {
    }

    /**
     * Retrieves button-specified settings
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $resourceId = $this->getResourceId();
        if ($this->moduleChecker->isConfigured() && $resourceId) {
            return [
                'label' => __('Translate'),
                'data_attribute' => [
                    'mage-init' => [
                        'Aheadworks_Langshop/js/translate-button' => [
                            'isDisabled' => $this->statusChecker->isProcessing(
                                $this->resourceType,
                                (string)$resourceId
                            ),
                            'successMessage' => __('Processing %1 translation.', $this->resourceType),
                            'failedMessage' => __('Something went wong.'),
                            'translateUrl' => $this->urlBuilder->getUrl(self::TRANSLATE_URL),
                            'ajaxParams' => [
                                'resource_type' => $this->resourceType,
                                'resource_id' => $resourceId
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
     * @return int|null
     */
    protected function getResourceId(): ?int
    {
        $resourceId = $this->request->getParam($this->resourceIdParam);
        return $resourceId ? (int)$resourceId : null;
    }
}
