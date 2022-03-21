<?php
namespace Aheadworks\Langshop\Model\Source\Locale\Scope;

use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Escaper as LocaleScopeEscaper;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type as LocaleScopeTypeSourceModel;
use Aheadworks\Langshop\Model\Source\AbstractOption as AbstractOptionSourceModel;

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
     * @param StoreManagerInterface $storeManager
     * @param LocaleScopeEscaper $localeScopeEscaper
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        LocaleScopeEscaper $localeScopeEscaper
    ) {
        $this->storeManager = $storeManager;
        $this->localeScopeEscaper = $localeScopeEscaper;
    }

    /**
     * Retrieve the list of scopes, allowed for translation
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
                //TODO: LSM2-26 use the separate class to encode/decode scope UID
                'value' => LocaleScopeTypeSourceModel::WEBSITE . '_' . $website->getId(),
            ];
            $storeListOfWebsite = [];
            foreach ($storeList as $store) {
                if ($store->getWebsiteId() == $website->getId()) {
                    $storeListOfWebsite[] = [
                        'label' => $this->localeScopeEscaper->getSanitizedName($store->getName()),
                        //TODO: LSM2-26 use the separate class to encode/decode scope UID
                        'value' => LocaleScopeTypeSourceModel::STORE . '_' . $store->getId(),
                    ];
                }
            }
            $optionList[] = [
                'label' => __('Store views of %1', $this->localeScopeEscaper->getSanitizedName($website->getName())),
                //TODO: LSM2-26 use the separate class to encode/decode scope UID
                'value' => array_values($storeListOfWebsite),
            ];
        }

        return $optionList;
    }
}
