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
     * Magento admin route path: module/controller/action with optional extra segments
     */
    private const PATH_PATTERN = '#^[A-Za-z0-9*][A-Za-z0-9_\-/*]*$#';

    /**
     * Get url
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Json $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $path = (string) $this->getRequest()->getParam('path');
        $result->setData([
            'url' => $this->isAllowedPath($path) ? $this->getUrl($path) : null
        ]);

        return $result;
    }

    /**
     * Allow only Magento route paths, not full or external URLs
     *
     * @param string $path
     * @return bool
     */
    private function isAllowedPath(string $path): bool
    {
        if ($path === '' || str_contains($path, '://') || str_contains($path, '..')) {
            return false;
        }

        return (bool) preg_match(self::PATH_PATTERN, $path);
    }
}
