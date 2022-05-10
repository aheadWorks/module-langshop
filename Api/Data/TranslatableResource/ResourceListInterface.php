<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\TranslatableResource;

interface ResourceListInterface
{
    /**
     * Constants for internal keys
     */
    const ITEMS = 'data';
    const PAGINATION = 'pagination';

    /**
     * Get items
     *
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface[]|null
     */
    public function getItems(): ?array;

    /**
     * Set items
     *
     * @param \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface[] $items
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface
     */
    public function setItems(array $items): ResourceListInterface;

    /**
     * Get pagination
     *
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterface|null
     */
    public function getPagination(): ?PaginationInterface;

    /**
     * Set pagination
     *
     * @param \Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterface $pagination
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface
     */
    public function setPagination(PaginationInterface $pagination): ResourceListInterface;
}
