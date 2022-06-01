<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Controller\Adminhtml\Saas;

use Aheadworks\Langshop\Model\Status\StatusChecker;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json as ResultJson;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Status extends Action implements HttpPostActionInterface
{
    /**
     * @var StatusChecker
     */
    private StatusChecker $statusChecker;

    /**
     * @param StatusChecker $statusChecker
     * @param Context $context
     */
    public function __construct(
        StatusChecker $statusChecker,
        Context $context
    ) {
        $this->statusChecker = $statusChecker;
        parent::__construct($context);
    }

    /**
     * Checks if the translations for the resource is ready
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $result = [
            'success' => true
        ];

        $resourceType = (string) $this->getRequest()->getParam('resource_type');
        $resourceId = (int) $this->getRequest()->getParam('resource_id');

        if ($resourceType && $resourceId) {
            $result['success'] = $this->statusChecker->isTranslated($resourceType, $resourceId);
        }

        /** @var ResultJson $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        return $resultJson->setData($result);
    }
}
