<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\Schema\TranslatableResource;

interface FieldInterface
{
    /**
     * Set key
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): FieldInterface;

    /**
     * Get key
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): FieldInterface;

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): FieldInterface;

    /**
     * Get type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Set is translatable
     *
     * @param bool $isTranslatable
     * @return $this
     */
    public function setIsTranslatable(bool $isTranslatable): FieldInterface;

    /**
     * Is translatable
     *
     * @return bool
     */
    public function isTranslatable(): bool;

    /**
     * Set is title
     *
     * @param bool $isTitle
     * @return $this
     */
    public function setIsTitle(bool $isTitle): FieldInterface;

    /**
     * Is title
     *
     * @return bool
     */
    public function isTitle(): bool;

    /**
     * Set filter
     *
     * @param string $filter
     * @return $this
     */
    public function setFilter(string $filter): FieldInterface;

    /**
     * Get filter
     *
     * @return string
     */
    public function getFilter(): string;

    /**
     * Set filter options
     *
     * @param \Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldOptionInterface[] $filterOptions
     * @return $this
     */
    public function setFilterOptions(array $filterOptions): FieldInterface;

    /**
     * Get filter options
     *
     * @return \Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldOptionInterface[]
     */
    public function getFilterOptions(): array;

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder(int $sortOrder): FieldInterface;

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder(): int;

    /**
     * Set visible areas for field
     *
     * @param string[] $areas
     * @return $this
     */
    public function setVisibleOn(array $areas): FieldInterface;

    /**
     * Get visible areas for field
     *
     * @return string[]
     */
    public function getVisibleOn(): array;
}
