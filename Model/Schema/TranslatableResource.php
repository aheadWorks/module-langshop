<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Schema;

use Aheadworks\Langshop\Api\Data\Schema\ResourceInterface;
use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldInterface;
use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\SortingInterface;
use Magento\Framework\DataObject;

class TranslatableResource extends DataObject implements ResourceInterface
{
    /**
     * Set resource
     *
     * @param string $resource
     * @return $this
     */
    public function setResource(string $resource): ResourceInterface
    {
        return $this->setData(self::RESOURCE, $resource);
    }

    /**
     * Get resource
     *
     * @return string
     */
    public function getResource(): string
    {
        return $this->getData(self::RESOURCE);
    }

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): ResourceInterface
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
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): ResourceInterface
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon): ResourceInterface
    {
        return $this->setData(self::ICON, $icon);
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->getData(self::ICON);
    }

    /**
     * Set fields
     *
     * @param FieldInterface[] $fields
     * @return $this
     */
    public function setFields(array $fields): ResourceInterface
    {
        return $this->setData(self::FIELDS, $fields);
    }

    /**
     * Get fields
     *
     * @return FieldInterface[]
     */
    public function getFields(): array
    {
        return $this->getData(self::FIELDS);
    }

    /**
     * Set sorting
     *
     * @param SortingInterface[] $sorting
     * @return $this
     */
    public function setSorting(array $sorting): ResourceInterface
    {
        return $this->setData(self::SORTING, $sorting);
    }

    /**
     * Get sorting
     *
     * @return SortingInterface[]
     */
    public function getSorting(): array
    {
        return $this->getData(self::SORTING);
    }

    /**
     * Set view type
     *
     * @param string $viewType
     * @return $this
     */
    public function setViewType(string $viewType): ResourceInterface
    {
        return $this->setData(self::VIEW_TYPE, $viewType);
    }

    /**
     * Get view type
     *
     * @return string
     */
    public function getViewType(): string
    {
        return $this->getData(self::VIEW_TYPE);
    }
}
