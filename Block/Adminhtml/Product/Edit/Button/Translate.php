<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Block\Adminhtml\Product\Edit\Button;

use Aheadworks\Langshop\Model\Saas\ModuleChecker;
use Aheadworks\Langshop\Model\Saas\Request\Translate as TranslateRequest;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Translate implements ButtonProviderInterface
{
    /**
     * @var ModuleChecker
     */
    private ModuleChecker $moduleChecker;

    /**
     * @var TranslateRequest
     */
    private TranslateRequest $translateRequest;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param ModuleChecker $moduleChecker
     * @param TranslateRequest $translateRequest
     * @param RequestInterface $request
     */
    public function __construct(
        ModuleChecker $moduleChecker,
        TranslateRequest $translateRequest,
        RequestInterface $request
    ) {
        $this->moduleChecker = $moduleChecker;
        $this->translateRequest = $translateRequest;
        $this->request = $request;
    }

    /**
     * Retrieves button-specified settings
     *
     * @return array
     * @throws LocalizedException
     */
    public function getButtonData()
    {
        if ($this->moduleChecker->isConfigured() && $this->getResourceId()) {
            return [
                'label' => __('Translate'),
                'data_attribute' => [
                    'mage-init' => [
                        'Aheadworks_Langshop/js/translate-button' => [
                            'url' => $this->translateRequest->getUrl(),
                            'params' => $this->translateRequest->getParams(
                                'product',
                                $this->getResourceId()
                            )
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
        return (int) $this->request->getParam('id');
    }
}
