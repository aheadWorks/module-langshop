<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\PersistorPool;
use Magento\Eav\Model\ResourceModel\Entity\Attribute as AttributeResourceModel;
use Magento\Eav\Model\ResourceModel\Entity\AttributeFactory as AttributeResourceModelFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Proxy as AttributeResourceModelProxy;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;

class ResourceModel extends AttributeResourceModelProxy
{
    /**
     * @var AttributeResourceModelFactory
     */
    private AttributeResourceModelFactory $attributeResourceModelFactory;

    /**
     * @var PersistorPool
     */
    private PersistorPool $persistorPool;

    /**
     * @param AttributeResourceModelFactory $attributeResourceModelFactory
     * @param PersistorPool $persistorPool
     */
    public function __construct(
        AttributeResourceModelFactory $attributeResourceModelFactory,
        PersistorPool $persistorPool
    ) {
        $this->attributeResourceModelFactory = $attributeResourceModelFactory;
        $this->persistorPool = $persistorPool;
    }

    /**
     * @return AttributeResourceModel
     */
    public function _getSubject(): AttributeResourceModel
    {
        if (!$this->_subject) {
            $this->_subject = $this->attributeResourceModelFactory->create();
        }

        return $this->_subject;
    }

    /**
     * Save object data
     *
     * @param AbstractModel $object
     * @throws AlreadyExistsException
     */
    public function save(AbstractModel $object): void
    {
        parent::save($object);

        $storeId = (int) $object->getData('store_id') ?? Store::DEFAULT_STORE_ID;
        foreach ($this->persistorPool->get() as $persistor) {
            $persistor->save($object, $storeId);
        }
    }
}
