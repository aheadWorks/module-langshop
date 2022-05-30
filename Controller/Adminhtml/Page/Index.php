<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page as ResultPage;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var ResultPage $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage
            ->setActiveMenu('Aheadworks_Sarp::subscriptions')
            ->getConfig()->getTitle()->prepend((string) __('Langshop'));

        return $resultPage;
    }
}
