<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale\Scope\Record;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface as EntityInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record as EntityModel;
use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterfaceFactory as EntityInterfaceFactory;
use Aheadworks\Langshop\Model\ResourceModel\Locale\Scope\Record as ResourceModel;
use Aheadworks\Langshop\Model\ResourceModel\Locale\Scope\Record\Collection;
use Aheadworks\Langshop\Model\ResourceModel\Locale\Scope\Record\CollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Repository
{
    /**
     * @var ResourceModel
     */
    private ResourceModel $resourceModel;

    /**
     * @var EntityInterfaceFactory
     */
    private EntityInterfaceFactory $entityInterfaceFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var JoinProcessorInterface
     */
    private JoinProcessorInterface $extensionAttributesJoinProcessor;

    /**
     * @var DataObjectHelper
     */
    private DataObjectHelper $dataObjectHelper;

    /**
     * @var array
     */
    private array $instanceList = [];

    /**
     * @param ResourceModel $resourceModel
     * @param EntityInterfaceFactory $entityInterfaceFactory
     * @param CollectionFactory $collectionFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ResourceModel $resourceModel,
        EntityInterfaceFactory $entityInterfaceFactory,
        CollectionFactory $collectionFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resourceModel = $resourceModel;
        $this->entityInterfaceFactory = $entityInterfaceFactory;
        $this->collectionFactory = $collectionFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Load entity by id
     *
     * @param int $recordId
     * @param bool $forceLoad
     * @return EntityInterface
     * @throws NoSuchEntityException
     */
    public function getById($recordId, $forceLoad = false)
    {
        if ($forceLoad || !isset($this->instanceList[$recordId])) {
            /** @var EntityInterface $entity */
            $entity = $this->entityInterfaceFactory->create();
            $this->resourceModel->load($entity, $recordId);
            if (!$entity->getRecordId()) {
                throw NoSuchEntityException::singleField(
                    EntityInterface::RECORD_ID,
                    $recordId
                );
            }
            $this->instanceList[$recordId] = $entity;
        }
        return $this->instanceList[$recordId];
    }

    /**
     * Save given entity
     *
     * @param EntityInterface $entity
     * @return EntityInterface
     * @throws CouldNotSaveException
     */
    public function save(EntityInterface $entity)
    {
        try {
            $this->resourceModel->save($entity);
            $this->instanceList[$entity->getRecordId()] = $entity;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $entity;
    }

    /**
     * Delete given entity
     *
     * @param EntityInterface $entity
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(EntityInterface $entity)
    {
        try {
            $this->resourceModel->delete($entity);
            if (isset($this->instanceList[$entity->getRecordId()])) {
                unset($this->instanceList[$entity->getRecordId()]);
            }
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete entity by given id
     *
     * @param int $recordId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($recordId)
    {
        return $this->delete($this->getById($recordId));
    }

    /**
     * Retrieve list of entities
     *
     * @return EntityInterface[]
     */
    public function getList(): array
    {
        $collection = $this->collectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            EntityInterface::class
        );

        $entityList = [];
        /** @var EntityModel $item */
        foreach ($collection->getItems() as $item) {
            $entityList[] = $this->getEntity($item);
        }

        return $entityList;
    }

    /**
     * Get default scope record
     *
     * @return EntityInterface|mixed
     */
    public function getDefault()
    {
        if (!isset($this->instanceList['default'])) {
            /** @var Collection $collection */
            $collection = $this->collectionFactory->create();
            $collection->addFilter(EntityInterface::SCOPE_ID, 0);

            $this->instanceList['default'] = $collection->getFirstItem();
        }

        return $this->instanceList['default'];
    }

    /**
     * Prepare object of entity interface
     *
     * @param EntityModel $model
     * @return EntityInterface
     */
    private function getEntity($model)
    {
        /** @var EntityInterface $object */
        $entity = $this->entityInterfaceFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $entity,
            $model->getData(),
            EntityInterface::class
        );
        return $entity;
    }
}
