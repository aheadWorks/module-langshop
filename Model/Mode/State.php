<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Mode;

use Exception;
use Magento\Framework\Exception\LocalizedException;

class State
{
    public const APP_BUILDER = 'app_builder';
    public const LANG_SHOP_APP = 'lang_shop_app';

    /**
     * @param Flag $flag
     */
    public function __construct(private readonly Flag $flag)
    {
    }

    /**
     * Set mode
     *
     * @throws Exception
     */
    public function setMode(string $mode): void
    {
        if (!in_array($mode, [self::APP_BUILDER, self::LANG_SHOP_APP])) {
            throw new LocalizedException(__('Invalid mode'));
        }
        $this->flag
            ->loadSelf()
            ->setFlagData($mode)
            ->save();
    }

    /**
     * Get mode
     *
     * @return string
     */
    public function getMode(): string
    {
        try {
            $flagValue = $this->flag
                ->loadSelf()
                ->getFlagData();
            $mode = $flagValue ?: self::LANG_SHOP_APP;
        } catch (LocalizedException) {
            $mode = self::LANG_SHOP_APP;
        }

        return $mode;
    }

    /**
     * Check if mode is app builder
     *
     * @return bool
     */
    public function isAppBuilderMode(): bool
    {
        return $this->getMode() === self::APP_BUILDER;
    }

    /**
     * Check if mode is lang shop app
     *
     * @return bool
     */
    public function isLangShopAppMode(): bool
    {
        return $this->getMode() === self::LANG_SHOP_APP;
    }
}
