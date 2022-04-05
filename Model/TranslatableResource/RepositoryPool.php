<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Magento\Framework\Exception\NoSuchEntityException;

class RepositoryPool
{
    /**
     * @var array
     */
    private $repositories;

    /**
     * @param array $repositories
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
     * @throws NoSuchEntityException
     */
    public function get(string $resourceType)
    {
        $repository = $this->repositories[$resourceType] ?? null;
        if (!$repository) {
            throw new NoSuchEntityException(__('Resource with type = "%1" does not exist.', $resourceType));
        }

        return $repository;
    }
}
