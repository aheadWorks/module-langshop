<?php
namespace Aheadworks\Langshop\Model\Locale\ScopeRecord;

use Aheadworks\Langshop\Model\ResourceModel\Locale\ScopeRecord as ResourceModel;
use Aheadworks\Langshop\Model\Locale\ScopeRecordInterface as EntityInterface;
use Aheadworks\Langshop\Model\Locale\ScopeRecordInterfaceFactory as EntityInterfaceFactory;
use Aheadworks\Langshop\Model\Locale\ScopeRecord as EntityModel;
use Aheadworks\Langshop\Model\ResourceModel\Locale\ScopeRecord\Collection;
use Aheadworks\Langshop\Model\ResourceModel\Locale\ScopeRecord\CollectionFactory;
use Aheadworks\Langshop\Model\Locale\ScopeRecord\SearchResultsInterface
    as SearchResultsInterface;
use Aheadworks\Langshop\Model\Locale\ScopeRecord\SearchResultsInterfaceFactory
    as SearchResultsInterfaceFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchCriteriaInterface;

class Repository
{
    /**
     * @var ResourceModel
     */
    private $resourceModel;

    /**
     * @var EntityInterfaceFactory
     */
    private $entityInterfaceFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var array
     */
    private $instanceList = [];

    /**
     * @param ResourceModel $resourceModel
     * @param EntityInterfaceFactory $entityInterfaceFactory
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ResourceModel $resourceModel,
        EntityInterfaceFactory $entityInterfaceFactory,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        CollectionProcessorInterface $collectionProcessor,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resourceModel = $resourceModel;
        $this->entityInterfaceFactory = $entityInterfaceFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
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
     * Retrieve list of entities matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            EntityInterface::class
        );
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());

        $entityList = [];
        /** @var EntityModel $item */
        foreach ($collection->getItems() as $item) {
            $entityList[] = $this->getEntity($item);
        }
        $searchResults->setItems($entityList);

        return $searchResults;
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
