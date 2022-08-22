<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Schema\TranslatableResource;

use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldInterface;
use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldOptionInterface;
use Magento\Framework\DataObject;

class Field extends DataObject implements FieldInterface
{
    /**
     * Constants for internal keys
     */
    private const KEY = 'key';
    private const LABEL = 'label';
    private const TYPE = 'type';
    private const IS_TRANSLATABLE = 'isTranslatable';
    private const IS_TITLE = 'is_title';
    private const FILTER = 'filter';
    private const FILTER_OPTIONS = 'filter_options';
    private const SORT_ORDER = 'sortOrder';
    private const VISIBLE_ON = 'visible_on';

    /**
     * Set key
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): FieldInterface
    {
        return $this->setData(self::KEY, $key);
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey(): string
    {
        return  $this->getData(self::KEY);
    }

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): FieldInterface
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->getData(self::LABEL);
    }

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): FieldInterface
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Set is translatable
     *
     * @param bool $isTranslatable
     * @return $this
     */
    public function setIsTranslatable(bool $isTranslatable): FieldInterface
    {
        return $this->setData(self::IS_TRANSLATABLE, $isTranslatable);
    }

    /**
     * Is translatable
     *
     * @return bool
     */
    public function isTranslatable(): bool
    {
        return $this->getData(self::IS_TRANSLATABLE);
    }

    /**
     * Set is title
     *
     * @param bool $isTitle
     * @return $this
     */
    public function setIsTitle(bool $isTitle): FieldInterface
    {
        return $this->setData(self::IS_TITLE, $isTitle);
    }

    /**
     * Is title
     *
     * @return bool
     */
    public function isTitle(): bool
    {
        return $this->getData(self::IS_TITLE);
    }

    /**
     * Set filter
     *
     * @param string $filter
     * @return $this
     */
    public function setFilter(string $filter): FieldInterface
    {
        return $this->setData(self::FILTER, $filter);
    }

    /**
     * Get filter
     *
     * @return string
     */
    public function getFilter(): string
    {
        return $this->getData(self::FILTER);
    }

    /**
     * Set filter options
     *
     * @param FieldOptionInterface[] $filterOptions
     * @return $this
     */
    public function setFilterOptions(array $filterOptions): FieldInterface
    {
        return $this->setData(self::FILTER_OPTIONS, $filterOptions);
    }

    /**
     * Get filter options
     *
     * @return FieldOptionInterface[]
     */
    public function getFilterOptions(): array
    {
        return $this->getData(self::FILTER_OPTIONS);
    }

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder(int $sortOrder): FieldInterface
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder(): int
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * Set visible areas for field
     *
     * @param string[] $areas
     * @return $this
     */
    public function setVisibleOn(array $areas): FieldInterface
    {
        return $this->setData(self::VISIBLE_ON, $areas);
    }

    /**
     * Get visible areas for field
     *
     * @return string[]
     */
    public function getVisibleOn(): array
    {
        return $this->getData(self::VISIBLE_ON);
    }
}
