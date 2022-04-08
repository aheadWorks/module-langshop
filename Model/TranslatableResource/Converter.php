<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterfaceFactory;
use Magento\Framework\Data\Collection\AbstractDb as AbstractCollection;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class Converter
{
    /**
     * @var EntityAttribute
     */
    private $entityAttribute;

    /**
     * @var FieldInterfaceFactory
     */
    private $fieldFactory;

    /**
     * @var PaginationInterfaceFactory
     */
    private $paginationFactory;

    /**
     * @var ResourceListInterfaceFactory
     */
    private $resourceListFactory;

    /**
     * @var TranslatableResourceInterfaceFactory
     */
    private $resourceFactory;

    /**
     * @param EntityAttribute $entityAttribute
     * @param FieldInterfaceFactory $fieldFactory
     * @param PaginationInterfaceFactory $paginationFactory
     * @param ResourceListInterfaceFactory $resourceListFactory
     * @param TranslatableResourceInterfaceFactory $resourceFactory
     */
    public function __construct(
        EntityAttribute $entityAttribute,
        FieldInterfaceFactory $fieldFactory,
        PaginationInterfaceFactory $paginationFactory,
        ResourceListInterfaceFactory $resourceListFactory,
        TranslatableResourceInterfaceFactory $resourceFactory
    ) {
        $this->entityAttribute = $entityAttribute;
        $this->fieldFactory = $fieldFactory;
        $this->paginationFactory = $paginationFactory;
        $this->resourceListFactory = $resourceListFactory;
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * Converts the model to translatable resource
     *
     * @param DataObject $item
     * @param string $resourceType
     * @return TranslatableResourceInterface
     * @throws LocalizedException
     */
    public function convert(DataObject $item, string $resourceType): TranslatableResourceInterface
    {
        $fields = [];
        foreach ($this->entityAttribute->getList($resourceType) as $attribute) {
            /** @var Field $field */
            $field = $this->fieldFactory->create()
                ->setKey($attribute->getCode())
                ->setValue($item->getData($attribute->getCode()));

            /**
             * The fields must be represented as plain array, not object,
             * otherwise we'll lose the keys (locales) in the value
             * @see \Magento\Framework\Reflection\DataObjectProcessor::buildOutputDataArray()
             */
            $fields[] = $field->getData();
        }

        return $this->resourceFactory->create()
            ->setResourceId((int) $item->getId())
            ->setResourceType($resourceType)
            ->setFields($fields);
    }

    /**
     * Converts the collection to translatable resource list
     *
     * @param AbstractCollection $collection
     * @param string $resourceType
     * @return ResourceListInterface
     * @throws LocalizedException
     */
    public function convertCollection(AbstractCollection $collection, string $resourceType): ResourceListInterface
    {
        $resources = [];
        foreach ($collection->getItems() as $item) {
            $resources[] = $this->convert($item, $resourceType);
        }

        $pagination = $this->paginationFactory->create()
            ->setPage($collection->getCurPage())
            ->setPageSize($collection->getPageSize() ?: $collection->getSize())
            ->setTotalPages($collection->getLastPageNumber())
            ->setTotalItems($collection->getSize());

        return $this->resourceListFactory->create()
            ->setItems($resources)
            ->setPagination($pagination);
    }
}
