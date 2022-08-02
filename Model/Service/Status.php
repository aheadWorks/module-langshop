<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\StatusInterface;
use Aheadworks\Langshop\Api\StatusManagementInterface;
use Aheadworks\Langshop\Model\ResourceModel\Status\CollectionFactory as StatusCollectionFactory;
use Aheadworks\Langshop\Model\ResourceModel\StatusFactory as StatusResourceFactory;
use Aheadworks\Langshop\Model\Status as StatusModel;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class Status implements StatusManagementInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var StatusCollectionFactory
     */
    private StatusCollectionFactory $statusCollectionFactory;

    /**
     * @var StatusResourceFactory
     */
    private StatusResourceFactory $statusResourceFactory;

    /**
     * @param CollectionProcessorInterface $collectionProcessor
     * @param StatusCollectionFactory $statusCollectionFactory
     * @param StatusResourceFactory $statusResourceFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        StatusCollectionFactory $statusCollectionFactory,
        StatusResourceFactory $statusResourceFactory
    ) {
        $this->collectionProcessor = $collectionProcessor;
        $this->statusCollectionFactory = $statusCollectionFactory;
        $this->statusResourceFactory = $statusResourceFactory;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array
    {
        $statusCollection = $this->statusCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $statusCollection);

        /** @var StatusInterface[] $statuses */
        $statuses = $statusCollection->getItems();

        return $statuses;
    }

    /**
     * @inheritDoc
     */
    public function save(StatusInterface $status): void
    {
        /** @var StatusModel $status */
        $this->statusResourceFactory->create()->save($status);
    }

    /**
     * @inheritDoc
     */
    public function delete(StatusInterface $status): void
    {
        /** @var StatusModel $status */
        $this->statusResourceFactory->create()->delete($status);
    }
}
