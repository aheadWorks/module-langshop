<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Controller\Adminhtml\Page;

use Aheadworks\Langshop\Model\Config\ListToTranslate as ListToTranslateConfig;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page as ResultPage;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Aheadworks\Langshop\Model\Mode\State;
use Aheadworks\Langshop\Model\Saas\UrlBuilder;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * Actions processed without secret key validation
     *
     * @var string[]
     */
    protected $_publicActions = [
        'index'
    ];

    /**
     * @var ListToTranslateConfig
     */
    private ListToTranslateConfig $listToTranslateConfig;

    /**
     * @param Context $context
     * @param ListToTranslateConfig $listToTranslateConfig
     * @param State $state
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(
        Context $context,
        ListToTranslateConfig $listToTranslateConfig,
        private readonly State $state,
        private readonly UrlBuilder $urlBuilder
    ) {
        parent::__construct($context);
        $this->listToTranslateConfig = $listToTranslateConfig;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $this->addConfigNotice();

        if ($this->state->isAppBuilderMode()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath($this->urlBuilder->getAppBuilderAppPage());
            return $resultRedirect;
        }

        /** @var ResultPage $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage
            ->setActiveMenu('Aheadworks_Langshop::langshop')
            ->getConfig()->getTitle()->prepend((string) __('Langshop'));

        return $resultPage;
    }

    /**
     * Adds notice if configuration is empty
     */
    private function addConfigNotice(): void
    {
        if (!$this->listToTranslateConfig->getValue()) {
            $message = __(
                'Select locales that require translation in the <a href="%url">configuration</a>.',
                [
                    'url' => $this->getUrl(
                        'adminhtml/system_config/edit',
                        ['section' => 'aw_ls']
                    )
                ]
            );

            $this->messageManager->addComplexNoticeMessage(
                'aw_ls_admin_message',
                ['message' => $message->render()]
            );
        }
    }
}
