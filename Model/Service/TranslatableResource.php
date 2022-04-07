<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterfaceFactory;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Api\TranslatableResourceManagementInterface;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Converter;
use Aheadworks\Langshop\Model\TranslatableResource\RepositoryPool;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;

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
     * @var PaginationInterfaceFactory
     */
    private $paginationFactory;

    /**
     * @var ResourceListInterfaceFactory
     */
    private $resourceListFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ProcessorInterface
     */
    private $dataProcessor;

    /**
     * @param Converter $converter
     * @param RepositoryPool $repositoryPool
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param PaginationInterfaceFactory $paginationFactory
     * @param ResourceListInterfaceFactory $resourceListFactory
     * @param RequestInterface $request
     * @param ProcessorInterface $dataProcessor
     */
    public function __construct(
        Converter $converter,
        RepositoryPool $repositoryPool,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        PaginationInterfaceFactory $paginationFactory,
        ResourceListInterfaceFactory $resourceListFactory,
        RequestInterface $request,
        ProcessorInterface $dataProcessor
    ) {
        $this->converter = $converter;
        $this->repositoryPool = $repositoryPool;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->paginationFactory = $paginationFactory;
        $this->resourceListFactory = $resourceListFactory;
        $this->request = $request;
        $this->dataProcessor = $dataProcessor;
    }

    /**
     * @inheritDoc
     */
    public function getList(string $resourceType): ResourceListInterface
    {
        $repository = $this->repositoryPool->get($resourceType);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $params = $this->request->getParams();
        $params = $this->dataProcessor->process($params);

        $items = $repository->getList($searchCriteria);

        $resources = [];
        foreach ($items as $item) {
            $resources[] = $this->converter->convert($item, $resourceType);
        }

        $pagination = $this->paginationFactory->create()
            ->setPage($items->getCurPage())
            ->setPageSize($items->getPageSize() ?: $items->getSize())
            ->setTotalPages($items->getLastPageNumber())
            ->setTotalItems($items->getSize());

        return $this->resourceListFactory->create()
            ->setItems($resources)
            ->setPagination($pagination);
    }

    /**
     * @inheritDoc
     */
    public function getById(string $resourceType, int $resourceId): TranslatableResourceInterface
    {
        $repository = $this->repositoryPool->get($resourceType);

        $params = $this->request->getParams();
        $params = $this->dataProcessor->process($params);

        $item = $repository->get($resourceId);

        return $this->converter->convert($item, $resourceType);
    }

    /**
     * @inheritDoc
     */
    public function save(string $resourceType, int $resourceId): TranslatableResourceInterface
    {
        $repository = $this->repositoryPool->get($resourceType);

        $resource = $repository->get($resourceId);
        $repository->save($resource);

        return $this->getById($resourceType, $resourceId);
    }
}
