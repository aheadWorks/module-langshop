<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Controller\Adminhtml\Saas;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Redirect extends Action implements HttpGetActionInterface
{
    /**
     * Get url
     *
     * @return ResultInterface|Json
     */
    public function execute()
    {
        /** @var Json $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $path = $this->getRequest()->getParam('path');
        $result->setData([
            'url' => $path ? $this->getUrl($path) : null
        ]);

        return $result;
    }
}
