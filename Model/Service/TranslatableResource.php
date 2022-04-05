<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Api\TranslatableResourceManagementInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Converter;
use Aheadworks\Langshop\Model\TranslatableResource\RepositoryPool;
use Magento\Framework\Api\SearchCriteriaBuilder;

class TranslatableResource implements TranslatableResourceManagementInterface
{
    /**
     * @var Converter
     */
    private $converter;

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
     * @param Converter $converter
     * @param RepositoryPool $repositoryPool
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ResourceListInterfaceFactory $resourceListFactory
     */
    public function __construct(
        Converter $converter,
        RepositoryPool $repositoryPool,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ResourceListInterfaceFactory $resourceListFactory
    ) {
        $this->converter = $converter;
        $this->repositoryPool = $repositoryPool;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->resourceListFactory = $resourceListFactory;
    }

    /**
     * @inheritDoc
     */
    public function getList(string $resourceType): ResourceListInterface
    {
        $repository = $this->repositoryPool->get($resourceType);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $items = $repository->getList($searchCriteria)->getItems();

        $resources = [];
        foreach ($items as $item) {
            $resources[] = $this->converter->convert($item, $resourceType);
        }

        return $this->resourceListFactory->create()->setItems($resources);
    }

    /**
     * @inheritDoc
     */
    public function getById(string $resourceType, int $resourceId): TranslatableResourceInterface
    {
        $repository = $this->repositoryPool->get($resourceType);
        $item = $repository->getById($resourceId);

        return $this->converter->convert($item, $resourceType);
    }

    /**
     * @inheritDoc
     */
    public function save(string $resourceType, int $resourceId): TranslatableResourceInterface
    {
        $repository = $this->repositoryPool->get($resourceType);

        $resource = $repository->getById($resourceId);
        $repository->save($resource);

        return $this->getById($resourceType, $resourceId);
    }
}
