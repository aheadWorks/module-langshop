<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FieldInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterfaceFactory;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Magento\Framework\Data\Collection\AbstractDb as Collection;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

class Converter
{
    /**
     * @var FieldInterfaceFactory
     */
    private FieldInterfaceFactory $fieldFactory;

    /**
     * @var EntityAttributeProvider
     */
    private EntityAttributeProvider $entityAttributeProvider;

    /**
     * @var PaginationInterfaceFactory
     */
    private PaginationInterfaceFactory $paginationFactory;

    /**
     * @var ResourceListInterfaceFactory
     */
    private ResourceListInterfaceFactory $resourceListFactory;

    /**
     * @var TranslatableResourceInterfaceFactory
     */
    private TranslatableResourceInterfaceFactory $resourceFactory;

    /**
     * @param FieldInterfaceFactory $fieldFactory
     * @param EntityAttributeProvider $entityAttributeProvider
     * @param PaginationInterfaceFactory $paginationFactory
     * @param ResourceListInterfaceFactory $resourceListFactory
     * @param TranslatableResourceInterfaceFactory $resourceFactory
     */
    public function __construct(
        FieldInterfaceFactory $fieldFactory,
        EntityAttributeProvider $entityAttributeProvider,
        PaginationInterfaceFactory $paginationFactory,
        ResourceListInterfaceFactory $resourceListFactory,
        TranslatableResourceInterfaceFactory $resourceFactory
    ) {
        $this->fieldFactory = $fieldFactory;
        $this->entityAttributeProvider = $entityAttributeProvider;
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
        foreach ($this->entityAttributeProvider->getList($resourceType) as $attribute) {
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

        /** @var AbstractModel $item */
        return $this->resourceFactory->create()
            ->setResourceId((string) $item->getId())
            ->setResourceType($resourceType)
            ->setFields($fields);
    }

    /**
     * Converts the collection to translatable resource list
     *
     * @param Collection $collection
     * @param string $resourceType
     * @return ResourceListInterface
     * @throws LocalizedException
     */
    public function convertCollection(Collection $collection, string $resourceType): ResourceListInterface
    {
        $resources = [];
        foreach ($collection as $item) {
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
