<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Magento\Framework\Exception\NoSuchEntityException;

class Pool
{
    /**
     * @var RepositoryInterface[]
     */
    private array $repositories;

    /**
     * @param RepositoryInterface[] $repositories
     */
    public function __construct(
        array $repositories = []
    ) {
        $this->repositories = $repositories;
    }

    /**
     * Retrieves repository by specific type
     *
     * @param string $resourceType
     * @return RepositoryInterface
     * @throws NoSuchEntityException
     */
    public function get(string $resourceType): RepositoryInterface
    {
        $repository = $this->repositories[$resourceType] ?? null;
        if (!$repository) {
            throw new NoSuchEntityException(__('Resource with type = "%1" does not exist.', $resourceType));
        }

        return $repository;
    }
}
