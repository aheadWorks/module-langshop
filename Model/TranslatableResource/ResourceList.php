<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Magento\Framework\Model\AbstractModel;

class ResourceList extends AbstractModel implements ResourceListInterface
{
    /**
     * @inheritDoc
     */
    public function getItems(): ?array
    {
        return $this->getData(self::ITEMS);
    }

    /**
     * @inheritDoc
     */
    public function setItems(array $items): ResourceListInterface
    {
        return $this->setData(self::ITEMS, $items);
    }

    /**
     * @inheritDoc
     */
    public function getPagination(): ?PaginationInterface
    {
        return $this->getData(self::PAGINATION);
    }

    /**
     * @inheritDoc
     */
    public function setPagination(PaginationInterface $pagination): ResourceListInterface
    {
        return $this->setData(self::PAGINATION, $pagination);
    }
}
