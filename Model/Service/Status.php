<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\StatusInterface;
use Aheadworks\Langshop\Api\StatusManagementInterface;
use Aheadworks\Langshop\Model\ResourceModel\Status\CollectionFactory as StatusCollectionFactory;
use Aheadworks\Langshop\Model\ResourceModel\StatusFactory as StatusResourceFactory;
use Aheadworks\Langshop\Model\Status as StatusModel;

class Status implements StatusManagementInterface
{
    /**
     * @var StatusCollectionFactory
     */
    private StatusCollectionFactory $statusCollectionFactory;

    /**
     * @var StatusResourceFactory
     */
    private StatusResourceFactory $statusResourceFactory;

    /**
     * @param StatusCollectionFactory $statusCollectionFactory
     * @param StatusResourceFactory $statusResourceFactory
     */
    public function __construct(
        StatusCollectionFactory $statusCollectionFactory,
        StatusResourceFactory $statusResourceFactory
    ) {
        $this->statusCollectionFactory = $statusCollectionFactory;
        $this->statusResourceFactory = $statusResourceFactory;
    }

    /**
     * @inheritDoc
     */
    public function getList(string $resourceType, int $resourceId): array
    {
        $statusCollection = $this->statusCollectionFactory->create()
            ->addFieldToFilter('resource_type', $resourceType)
            ->addFieldToFilter('resource_id', (string) $resourceId);

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
