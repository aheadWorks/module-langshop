<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource;

use Magento\Catalog\Model\Category as ModelCategory;
use Magento\Catalog\Model\ResourceModel\Category as CategoryResourceModel;
use Magento\Catalog\Model\ResourceModel\Category\Proxy as CategoryResourceModelProxy;
use Magento\Catalog\Model\ResourceModel\CategoryFactory as CategoryResourceModelFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Store\Model\Store;

class Category extends CategoryResourceModelProxy
{
    /**
     * @var CategoryResourceModelFactory
     */
    private CategoryResourceModelFactory $categoryResourceModelFactory;

    /**
     * @var EavConfig
     */
    private EavConfig $eavConfig;

    /**
     * @var string[]
     */
    private array $requiredAttributes;

    /**
     * @param CategoryResourceModelFactory $categoryResourceModelFactory
     * @param EavConfig $eavConfig
     * @param array $requiredAttributes
     */
    public function __construct(
        CategoryResourceModelFactory $categoryResourceModelFactory,
        EavConfig $eavConfig,
        array $requiredAttributes = []
    ) {
        $this->categoryResourceModelFactory = $categoryResourceModelFactory;
        $this->eavConfig = $eavConfig;
        $this->requiredAttributes = $requiredAttributes;
    }

    /**
     * Get proxied instance
     *
     * @return CategoryResourceModel
     */
    public function _getSubject(): CategoryResourceModel
    {
        // @phpstan-ignore-next-line
        if (!isset($this->_subject)) {
            $this->_subject = $this->categoryResourceModelFactory->create();
        }

        return $this->_subject;
    }

    /**
     * Save object data
     *
     * @param ModelCategory $object
     * @return $this
     * @throws LocalizedException
     * @throws \Exception
     */
    public function save(AbstractModel $object): CategoryResourceModel
    {
        $this->loadRequiredAttributesData($object);
        return parent::save($object);
    }

    /**
     * Load required attributes data
     *
     * @param ModelCategory $entity
     * @return Category
     * @throws LocalizedException
     */
    private function loadRequiredAttributesData(AbstractModel $entity): Category
    {
        $tableAttributes = [];

        foreach ($this->requiredAttributes as $attributeCode) {
            $attribute = $this->eavConfig->getAttribute('catalog_category', $attributeCode);
            $tableAttributes[$attribute->getBackendTable()][] = $attribute->getAttributeId();
        }

        $entityTypeId = $this->eavConfig->getEntityType('catalog_category')->getEntityTypeId();
        $connection = $this->getConnection();

        foreach ($tableAttributes as $table => $ids) {
            $select = $connection->select()
                ->from(
                    ['e' => $this->getEntityTable()],
                    []
                )->joinLeft(
                    ['eav_attr' => $this->getTable('eav_attribute')],
                    'eav_attr.entity_type_id = ' . $entityTypeId,
                    ['attribute_code']
                )->joinLeft(
                    ['eavt' => $table],
                    'eavt.entity_id = e.entity_id'
                    . ' AND eavt.attribute_id = eav_attr.attribute_id'
                    . ' AND eavt.store_id = ' . $entity->getStoreId(),
                    []
                )->joinLeft(
                    ['default_eavt' => $table],
                    'eavt.entity_id = e.entity_id'
                    . ' AND default_eavt.attribute_id = eav_attr.attribute_id'
                    . ' AND eavt.store_id = ' . Store::DEFAULT_STORE_ID,
                    []
                )->columns([
                    'value' => $connection->getIfNullSql('eavt.value', 'default_eavt.value')
                ])->where(
                    " e.entity_id = ?",
                    $entity->getEntityId()
                )->where(
                    'eav_attr.attribute_id IN (?)',
                    $ids
                );

            $data = $connection->fetchAll($select);
            foreach ($data as $value) {
                $entity->setData($value['attribute_code'], $value['value']);
            }
        }

        return $this;
    }
}
