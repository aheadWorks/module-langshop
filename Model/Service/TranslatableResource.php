<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Api\TranslatableResourceManagementInterface;
use Aheadworks\Langshop\Model\Data\Processor\Pool as DataProcessorPool;
use Aheadworks\Langshop\Model\TranslatableResource\Converter;
use Aheadworks\Langshop\Model\TranslatableResource\Repository\Pool as RepositoryPool;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteriaBuilder;

class TranslatableResource implements TranslatableResourceManagementInterface
{
    /**
     * @param Converter $converter
     * @param RepositoryPool $repositoryPool
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DataProcessorPool $dataProcessorPool
     */
    public function __construct(
        private Converter $converter,
        private RepositoryPool $repositoryPool,
        private SearchCriteriaBuilder $searchCriteriaBuilder,
        private DataProcessorPool $dataProcessorPool
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getList(
        string $resourceType,
        array $locale = [],
        ?int $page = null,
        ?int $pageSize = null,
        ?string $sortBy = null,
        ?array $filter = []
    ): ResourceListInterface {
        $repository = $this->repositoryPool->get($resourceType);

        $dataProcessor = $this->dataProcessorPool->get($resourceType);
        $params = $dataProcessor->process([
            'resourceType' => $resourceType,
            'locale' => $locale,
            'page' => $page,
            'pageSize' => $pageSize,
            'sortBy' => $sortBy,
            'filter' => $filter
        ]);

        $searchCriteriaBuilder = $this->searchCriteriaBuilder
            ->setCurrentPage($params['page'])
            ->setPageSize($params['pageSize'])
            ->setSortOrders($params['sortBy']);

        /** @var Filter $filter */
        foreach ($params['filter'] as $filter) {
            $searchCriteriaBuilder->addFilters([$filter]);
        }

        $collection = $repository->getList($searchCriteriaBuilder->create(), $params['locale']);

        return $this->converter->convertCollection($collection, $resourceType);
    }

    /**
     * @inheritDoc
     */
    public function getById(string $resourceType, string $resourceId, array $locale = []): TranslatableResourceInterface
    {
        $repository = $this->repositoryPool->get($resourceType);

        $params = $this->dataProcessor->process([
            'resourceType' => $resourceType,
            'locale' => $locale
        ]);

        $item = $repository->get($resourceId, $params['locale']);

        return $this->converter->convert($item, $resourceType);
    }

    /**
     * @inheritDoc
     */
    public function save(string $resourceType, string $resourceId, array $translations): TranslatableResourceInterface
    {
        $repository = $this->repositoryPool->get($resourceType);
        $repository->save($resourceId, $translations);

        return $this->getById($resourceType, $resourceId);
    }
}
