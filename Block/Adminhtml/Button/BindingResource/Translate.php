<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Block\Adminhtml\Button\BindingResource;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Aheadworks\Langshop\Model\Saas\ModuleChecker;
use Aheadworks\Langshop\Model\Status\StatusChecker;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding as BindingResourceModel;
use Aheadworks\Langshop\Block\Adminhtml\Button\Translate as TranslateButton;

class Translate extends TranslateButton
{
    /**
     * @param UrlInterface $urlBuilder
     * @param ModuleChecker $moduleChecker
     * @param RequestInterface $request
     * @param StatusChecker $statusChecker
     * @param BindingResourceModel $bindingResourceModel
     * @param string $resourceType
     * @param string $resourceIdParam
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ModuleChecker $moduleChecker,
        RequestInterface $request,
        StatusChecker $statusChecker,
        private readonly BindingResourceModel $bindingResourceModel,
        string $resourceType,
        string $resourceIdParam = 'id'
    ) {
        parent::__construct($urlBuilder, $moduleChecker, $request, $statusChecker, $resourceType, $resourceIdParam);
    }

    /**
     * Retrieves identifier for the current resource
     *
     * @return int|null
     * @throws LocalizedException
     */
    protected function getResourceId(): ?int
    {
        $translatedResourceId = (int)$this->request->getParam($this->resourceIdParam);
        if (!$translatedResourceId) {
            return null;
        }

        return $this->bindingResourceModel->getOriginalResourceId($this->resourceType, $translatedResourceId);
    }
}
