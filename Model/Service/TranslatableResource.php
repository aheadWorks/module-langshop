<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterfaceFactory;
use Aheadworks\Langshop\Api\TranslatableResourceManagementInterface;

class TranslatableResource implements TranslatableResourceManagementInterface
{
    /**
     * @var ResourceListInterfaceFactory
     */
    private $resourceListFactory;

    /**
     * @var TranslatableResourceInterfaceFactory
     */
    private $resourceFactory;

    /**
     * @param ResourceListInterfaceFactory $resourceListFactory
     * @param TranslatableResourceInterfaceFactory $resourceFactory
     */
    public function __construct(
        ResourceListInterfaceFactory $resourceListFactory,
        TranslatableResourceInterfaceFactory $resourceFactory
    ) {
        $this->resourceListFactory = $resourceListFactory;
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * @inheritDoc
     */
    public function getList(string $resourceType): ResourceListInterface
    {
        return $this->resourceListFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function getById(string $resourceType, int $resourceId): TranslatableResourceInterface
    {
        return $this->resourceFactory->create()
            ->setResourceId($resourceId)
            ->setResourceType($resourceType);
    }

    /**
     * @inheritDoc
     */
    public function save(string $resourceType, int $resourceId): TranslatableResourceInterface
    {
        return $this->resourceFactory->create()
            ->setResourceId($resourceId)
            ->setResourceType($resourceType);
    }
}
