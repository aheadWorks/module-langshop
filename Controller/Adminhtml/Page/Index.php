<?php
declare(strict_types = 1);
namespace Aheadworks\Langshop\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage
            ->setActiveMenu('Aheadworks_Sarp::subscriptions')
            ->getConfig()->getTitle()->prepend(__('Langshop'));

        return $resultPage;
    }
}
