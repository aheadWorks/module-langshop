<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterface;
use Magento\Framework\DataObject;

class Pagination extends DataObject implements PaginationInterface
{
    /**
     * Constants for internal keys
     */
    private const PAGE = 'page';
    private const PAGE_SIZE = 'pageSize';
    private const TOTAL_PAGES = 'totalPages';
    private const TOTAL_ITEMS = 'totalItems';

    /**
     * Set page
     *
     * @param int $page
     * @return $this
     */
    public function setPage(int $page)
    {
        return $this->setData(self::PAGE, $page);
    }

    /**
     * Get page
     *
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->getData(self::PAGE);
    }

    /**
     * Set page size
     *
     * @param int $pageSize
     * @return $this
     */
    public function setPageSize(int $pageSize)
    {
        return $this->setData(self::PAGE_SIZE, $pageSize);
    }

    /**
     * Get page size
     *
     * @return int|null
     */
    public function getPageSize(): ?int
    {
        return $this->getData(self::PAGE_SIZE);
    }

    /**
     * Set total pages
     *
     * @param int $totalPages
     * @return $this
     */
    public function setTotalPages(int $totalPages)
    {
        return $this->setData(self::TOTAL_PAGES, $totalPages);
    }

    /**
     * Get total pages
     *
     * @return int|null
     */
    public function getTotalPages(): ?int
    {
        return $this->getData(self::TOTAL_PAGES);
    }

    /**
     * Set total items
     *
     * @param int $totalItems
     * @return $this
     */
    public function setTotalItems(int $totalItems)
    {
        return $this->setData(self::TOTAL_ITEMS, $totalItems);
    }

    /**
     * Get total items
     *
     * @return int|null
     */
    public function getTotalItems(): ?int
    {
        return $this->getData(self::TOTAL_ITEMS);
    }
}
