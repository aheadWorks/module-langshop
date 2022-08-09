<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale\Processor;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Model\Locale\ProcessorInterface;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type as LocaleScopeTypeSourceModel;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Website;

class Url implements ProcessorInterface
{
    /**
     * @var UrlInterface
     */
    private UrlInterface $url;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param UrlInterface $url
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        UrlInterface $url,
        StoreManagerInterface $storeManager
    ) {
        $this->url = $url;
        $this->storeManager = $storeManager;
    }

    /**
     * Add url value to locale
     *
     * @param LocaleInterface $locale
     * @param array $data
     * @return LocaleInterface
     * @throws LocalizedException
     */
    public function process(LocaleInterface $locale, array $data): LocaleInterface
    {
        $type = $data['scope_type'];

        if ($type == LocaleScopeTypeSourceModel::DEFAULT) {
            $storeId = $this->getStoreId();
        } elseif ($type == LocaleScopeTypeSourceModel::WEBSITE) {
            $storeId = $this->getStoreId($data['scope_id']);
        } else {
            $storeId = $data['scope_id'];
        }

        $url = $this->url->getBaseUrl(['_scope' => $storeId]);
        return $locale
            ->setPreviewUrl($url)
            ->setUrl($url);
    }

    /**
     * Gets default store identifier for the website
     *
     * @param int|null $websiteId
     * @return int
     * @throws LocalizedException
     */
    private function getStoreId(?int $websiteId = null): int
    {
        /** @var Website $website */
        $website = $this->storeManager->getWebsite($websiteId);

        return (int) $website->getDefaultStore()->getStoreId();
    }
}
