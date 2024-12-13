<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\ViewModel\Page;

use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Aheadworks\Langshop\Model\Saas\ModuleChecker;
use Aheadworks\Langshop\Model\Saas\UrlBuilder;
use Aheadworks\Langshop\Model\Mode\State as ModeState;

class Iframe implements ArgumentInterface
{
    /**
     * @param ModuleChecker $moduleChecker
     * @param UrlBuilder $urlBuilder
     * @param ModeState $modeState
     */
    public function __construct(
        private readonly ModuleChecker $moduleChecker,
        private readonly UrlBuilder $urlBuilder,
        private readonly ModeState $modeState
    ) {
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

    /**
     * Get redirect url
     *
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->urlBuilder->getRedirectUrl();
    }

    /**
     * Check if mode is lang shop app
     *
     * @return bool
     */
    public function isLangShopAppMode(): bool
    {
        return $this->modeState->isLangShopAppMode();
    }

    /**
     * Check if mode is app builder
     *
     * @return bool
     */
    public function isAppBuilderMode(): bool
    {
        return $this->modeState->isAppBuilderMode();
    }
}
