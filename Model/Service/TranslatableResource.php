<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Api\TranslatableResourceManagementInterface;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Converter;
use Aheadworks\Langshop\Model\TranslatableResource\RepositoryPool;
use Magento\Framework\Api\SearchCriteriaBuilder;

class TranslatableResource implements TranslatableResourceManagementInterface
{
    /**
     * @var Converter
     */
    private Converter $converter;

    /**
     * @var RepositoryPool
     */
    private RepositoryPool $repositoryPool;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var ProcessorInterface
     */
    private ProcessorInterface $dataProcessor;

    /**
     * @param Converter $converter
     * @param RepositoryPool $repositoryPool
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ProcessorInterface $dataProcessor
     */
    public function __construct(
        Converter $converter,
        RepositoryPool $repositoryPool,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProcessorInterface $dataProcessor
    ) {
        $this->converter = $converter;
        $this->repositoryPool = $repositoryPool;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->dataProcessor = $dataProcessor;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        string $resourceType,
        $locale = [],
        ?int $page = null,
        ?int $pageSize = null,
        ?string $sortBy = null,
        ?string $orderBy = null,
        ?array $filter = []
    ): ResourceListInterface {
        $repository = $this->repositoryPool->get($resourceType);

        $params = $this->dataProcessor->process([
            'resourceType' => $resourceType,
            'locale' => $locale,
            'page' => $page,
            'pageSize' => $pageSize,
            'filter' => $filter
        ]);

        if (isset($params['filters'])) {
            $this->searchCriteriaBuilder->addFilters($params['filters']);
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->setCurrentPage($params['page'])
            ->setPageSize($params['pageSize'])
            ->create();

        $collection = $repository->getList($searchCriteria, $params['locale']);

        return $this->converter->convertCollection($collection, $resourceType);
    }

    /**
     * @inheritDoc
     */
    public function getById(string $resourceType, int $resourceId, $locale = []): TranslatableResourceInterface
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
    public function save(string $resourceType, int $resourceId, array $translations): TranslatableResourceInterface
    {
        $repository = $this->repositoryPool->get($resourceType);
        $repository->save($resourceId, $translations);

        return $this->getById($resourceType, $resourceId);
    }
}
