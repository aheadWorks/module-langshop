<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Category;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Category as CategoryResource;
use Magento\Catalog\Model\Category;

class Save
{
    /**
     * Set url_key and is_anchor attributes to avoid url rewrites logic
     *
     * @param CategoryResource $categoryResource
     * @param Category $category
     * @return Category[]
     */
    public function beforeSave(
        CategoryResource $categoryResource,
        Category $category
    ): array {
        $entityId = $category->getEntityId();
        if ($entityId) {
            $data = $categoryResource->getAttributeRawValue(
                $entityId,
                ['url_key', 'is_anchor'],
                $category->getStoreId()
            );

            if (is_array($data) && $data) {
                foreach ($data as $key => $value) {
                    $category
                        ->setData($key, $value)
                        ->setOrigData($key, $value);
                }
            } else {
                $category->setData('use_default', [
                    'url_key' => true
                ]);
            }
        }

        return [
            $category
        ];
    }
}
