<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Magento\Framework\DataObject;

class ResourceList extends DataObject implements ResourceListInterface
{
    /**
     * Constants for internal keys
     */
    private const ITEMS = 'data';
    private const PAGINATION = 'pagination';

    /**
     * Get items
     *
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface[]|null
     */
    public function getItems(): ?array
    {
        return $this->getData(self::ITEMS);
    }

    /**
     * Set items
     *
     * @param \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface[] $items
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface
     */
    public function setItems(array $items): ResourceListInterface
    {
        return $this->setData(self::ITEMS, $items);
    }

    /**
     * Get pagination
     *
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterface|null
     */
    public function getPagination(): ?PaginationInterface
    {
        return $this->getData(self::PAGINATION);
    }

    /**
     * Set pagination
     *
     * @param \Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterface $pagination
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface
     */
    public function setPagination(PaginationInterface $pagination): ResourceListInterface
    {
        return $this->setData(self::PAGINATION, $pagination);
    }
}
