<?php
namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterface;
use Magento\Framework\Model\AbstractModel;

class Pagination extends AbstractModel implements PaginationInterface
{
    /**
     * @inheritDoc
     */
    public function setPage(int $page)
    {
        return $this->setData(self::PAGE, $page);
    }

    /**
     * @inheritDoc
     */
    public function getPage(): ?int
    {
        return $this->getData(self::PAGE);
    }

    /**
     * @inheritDoc
     */
    public function setPageSize(int $pageSize)
    {
        return $this->setData(self::PAGE_SIZE, $pageSize);
    }

    /**
     * @inheritDoc
     */
    public function getPageSize(): ?int
    {
        return $this->getData(self::PAGE_SIZE);
    }

    /**
     * @inheritDoc
     */
    public function setTotalPages(int $totalPages)
    {
        return $this->setData(self::TOTAL_PAGES, $totalPages);
    }

    /**
     * @inheritDoc
     */
    public function getTotalPages(): ?int
    {
        return $this->getData(self::TOTAL_PAGES);
    }

    /**
     * @inheritDoc
     */
    public function setTotalItems(int $totalItems)
    {
        return $this->setData(self::TOTAL_ITEMS, $totalItems);
    }

    /**
     * @inheritDoc
     */
    public function getTotalItems(): ?int
    {
        return $this->getData(self::TOTAL_ITEMS);
    }
}
