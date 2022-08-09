<?php
namespace Aheadworks\Langshop\Api\Data\TranslatableResource;

interface PaginationInterface
{
    public const PAGE = 'page';
    public const PAGE_SIZE = 'pageSize';
    public const TOTAL_PAGES = 'totalPages';
    public const TOTAL_ITEMS = 'totalItems';

    /**
     * Set page
     *
     * @param int $page
     * @return $this
     */
    public function setPage(int $page);

    /**
     * Get page
     *
     * @return int|null
     */
    public function getPage(): ?int;

    /**
     * Set page size
     *
     * @param int $pageSize
     * @return $this
     */
    public function setPageSize(int $pageSize);

    /**
     * Get page size
     *
     * @return int|null
     */
    public function getPageSize(): ?int;

    /**
     * Set total pages
     *
     * @param int $totalPages
     * @return $this
     */
    public function setTotalPages(int $totalPages);

    /**
     * Get total pages
     *
     * @return int|null
     */
    public function getTotalPages(): ?int;

    /**
     * Set total items
     *
     * @param int $totalItems
     * @return $this
     */
    public function setTotalItems(int $totalItems);

    /**
     * Get total items
     *
     * @return int|null
     */
    public function getTotalItems(): ?int;
}
