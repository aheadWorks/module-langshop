<?php
namespace Aheadworks\Langshop\Model\Locale\Processor;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Model\Locale\ProcessorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManager;

class Url implements ProcessorInterface
{
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @param UrlInterface $url
     * @param StoreManager $storeManager
     */
    public function __construct(
        UrlInterface $url,
        StoreManager $storeManager
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
    public function process(LocaleInterface $locale, array $data)
    {
        $type = $data['scope_type'];

        if ($type == 'default') {
            $storeId = $this->storeManager->getWebsite()->getDefaultStore()->getStoreId();
        } elseif ($type == 'website') {
            $storeId = $this->storeManager->getWebsite($data['scope_id'])->getDefaultStore()->getStoreId();
        } else {
            $storeId = $data['scope_id'];
        }

        $url = $this->url->getBaseUrl(['_scope' => $storeId]);
        return $locale
            ->setPreviewUrl($url)
            ->setUrl($url);
    }
}
