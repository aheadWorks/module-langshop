<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Processor\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\ProcessorInterface;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\DataObject;

class StoreLabel implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param Attribute $attribute
     */
    public function process(DataObject $attribute, array $data): array
    {
        if (isset($data['store_label'])) {
            $labels = $attribute->getFrontendLabels();
            $storeId = $attribute->getStoreId();
            foreach ($labels as $label) {
                if ($label->getStoreId() == $storeId) {
                    $label->setLabel($data['store_label']);
                }
            }
            $data['frontend_labels'] = $labels;
        }

        return $data;
    }
}
