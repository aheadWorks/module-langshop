<?php
namespace Aheadworks\Langshop\Model\Source\Locale\Scope;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Escaper;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type as LocaleScopeTypeSourceModel;

class ListToTranslate implements OptionSourceInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var array|null
     */
    private $optionList;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Escaper $escaper
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Escaper $escaper
    ) {
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
    }

    /**
     * Retrieve the list of scopes, allowed for translation
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->optionList !== null) {
            return $this->optionList;
        }

        $this->optionList = $this->getOptionList();

        return $this->optionList;
    }

    /**
     * Retrieve the list of options
     *
     * @return array
     */
    private function getOptionList()
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
                'label' => $this->getSanitizedName($website->getName()),
                //TODO: LSM2-26 use the separate class to encode/decode scope UID
                'value' => LocaleScopeTypeSourceModel::WEBSITE . '_' . $website->getId(),
            ];
            $storeListOfWebsite = [];
            foreach ($storeList as $store) {
                if ($store->getWebsiteId() == $website->getId()) {
                    $storeListOfWebsite[] = [
                        'label' => $this->getSanitizedName($store->getName()),
                        //TODO: LSM2-26 use the separate class to encode/decode scope UID
                        'value' => LocaleScopeTypeSourceModel::STORE . '_' . $store->getId(),
                    ];
                }
            }
            $optionList[] = [
                'label' => __('Store views of %1', $this->getSanitizedName($website->getName())),
                //TODO: LSM2-26 use the separate class to encode/decode scope UID
                'value' => array_values($storeListOfWebsite),
            ];
        }

        return $optionList;
    }

    /**
     * Retrieve sanitized name of the given scope
     *
     * @param string $scopeName
     *
     * @return string
     * TODO: consider refactoring to the separate sanitized-class
     * origin: \Magento\Store\Ui\Component\Listing\Column\Store\Options::sanitizeName
     */
    private function getSanitizedName($scopeName)
    {
        $matches = [];
        preg_match('/\$[:]*{(.)*}/', $scopeName, $matches);
        if (count($matches) > 0) {
            $sanitizedName = $this->escaper->escapeHtml(
                $this->escaper->escapeJs($scopeName)
            );
        } else {
            $sanitizedName = $this->escaper->escapeHtml(
                $scopeName
            );
        }

        return $sanitizedName;
    }
}
