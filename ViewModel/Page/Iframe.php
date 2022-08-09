<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\ViewModel\Page;

use Aheadworks\Langshop\Model\Saas\ModuleChecker;
use Aheadworks\Langshop\Model\Saas\UrlBuilder;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Iframe implements ArgumentInterface
{
    /**
     * @var ModuleChecker
     */
    private ModuleChecker $moduleChecker;

    /**
     * @var UrlBuilder
     */
    private UrlBuilder $urlBuilder;

    /**
     * @param ModuleChecker $moduleChecker
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(
        ModuleChecker $moduleChecker,
        UrlBuilder $urlBuilder
    ) {
        $this->moduleChecker = $moduleChecker;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Get source url
     *
     * @return string
     * @throws IntegrationException
     */
    public function getSourceUrl(): string
    {
        return $this->moduleChecker->isConfigured()
            ? $this->urlBuilder->getApplicationUrl()
            : $this->urlBuilder->getWizardUrl();
    }
}
