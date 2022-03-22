<?php
namespace Aheadworks\Langshop\Model\Source\Locale\Scope;

use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Escaper as LocaleScopeEscaper;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type as LocaleScopeTypeSourceModel;
use Aheadworks\Langshop\Model\Source\AbstractOption as AbstractOptionSourceModel;
use Aheadworks\Langshop\Model\Locale\Scope\UidResolver as LocaleScopeUidResolver;

class ListToTranslate extends AbstractOptionSourceModel
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LocaleScopeEscaper
     */
    private $localeScopeEscaper;

    /**
     * @var LocaleScopeUidResolver
     */
    private $localeScopeUidResolver;

    /**
     * @param StoreManagerInterface $storeManager
     * @param LocaleScopeEscaper $localeScopeEscaper
     * @param LocaleScopeUidResolver $localeScopeUidResolver
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        LocaleScopeEscaper $localeScopeEscaper,
        LocaleScopeUidResolver $localeScopeUidResolver
    ) {
        $this->storeManager = $storeManager;
        $this->localeScopeEscaper = $localeScopeEscaper;
        $this->localeScopeUidResolver = $localeScopeUidResolver;
    }

    /**
     * Retrieve the list of scopes, allowed for translation, where values are UIDs
     *
     * @return array
     */
    protected function getOptionList()
    {
        $optionList = [];
        $websiteList = $this->storeManager->getWebsites(
            false,
            false
        );
        $storeList = $this->storeManager->getStores(
            false,
            false
        );

        foreach ($websiteList as $website) {
            $optionList[] = [
                'label' => $this->localeScopeEscaper->getSanitizedName($website->getName()),
                'value' => $this->localeScopeUidResolver->getUid(
                    LocaleScopeTypeSourceModel::WEBSITE,
                    $website->getId()
                ),
            ];
            $storeListOfWebsite = [];
            foreach ($storeList as $store) {
                if ($store->getWebsiteId() == $website->getId()) {
                    $storeListOfWebsite[] = [
                        'label' => $this->localeScopeEscaper->getSanitizedName($store->getName()),
                        'value' => $this->localeScopeUidResolver->getUid(
                            LocaleScopeTypeSourceModel::STORE,
                            $store->getId()
                        ),
                    ];
                }
            }
            $optionList[] = [
                'label' => __('Store views of %1', $this->localeScopeEscaper->getSanitizedName($website->getName())),
                'value' => array_values($storeListOfWebsite),
            ];
        }

        return $optionList;
    }
}
