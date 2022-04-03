<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterfaceFactory;
use Aheadworks\Langshop\Api\TranslatableResourceManagementInterface;
use Aheadworks\Langshop\Model\TranslatableResource\RepositoryPool;
use Magento\Framework\Api\SearchCriteriaBuilder;

class TranslatableResource implements TranslatableResourceManagementInterface
{
    /**
     * @var RepositoryPool
     */
    private $repositoryPool;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ResourceListInterfaceFactory
     */
    private $resourceListFactory;

    /**
     * @var TranslatableResourceInterfaceFactory
     */
    private $resourceFactory;

    /**
     * @param RepositoryPool $repositoryPool
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ResourceListInterfaceFactory $resourceListFactory
     * @param TranslatableResourceInterfaceFactory $resourceFactory
     */
    public function __construct(
        RepositoryPool $repositoryPool,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ResourceListInterfaceFactory $resourceListFactory,
        TranslatableResourceInterfaceFactory $resourceFactory
    ) {
        $this->repositoryPool = $repositoryPool;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->resourceListFactory = $resourceListFactory;
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * @inheritDoc
     */
    public function getList(string $resourceType): ResourceListInterface
    {
        $resources = $this->repositoryPool->getRepository($resourceType)->getList(
            $this->searchCriteriaBuilder->create()
        );

        return $this->resourceListFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function getById(string $resourceType, int $resourceId): TranslatableResourceInterface
    {
        $resource = $this->repositoryPool->getRepository($resourceType)->getById($resourceId);

        return $this->resourceFactory->create()
            ->setResourceId($resourceId)
            ->setResourceType($resourceType);
    }

    /**
     * @inheritDoc
     */
    public function save(string $resourceType, int $resourceId): TranslatableResourceInterface
    {
        $repository = $this->repositoryPool->getRepository($resourceType);

        $resource = $repository->getById($resourceId);
        $repository->save($resource);

        return $this->getById($resourceType, $resourceId);
    }
}
