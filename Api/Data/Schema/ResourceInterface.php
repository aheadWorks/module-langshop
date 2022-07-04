<?php
namespace Aheadworks\Langshop\Api\Data\Schema;

interface ResourceInterface
{
    public const RESOURCE = 'resource';
    public const LABEL = 'label';
    public const DESCRIPTION = 'description';
    public const ICON = 'icon';
    public const FIELDS = 'fields';
    public const SORTING = 'sorting';
    public const VIEW_TYPE = 'viewType';

    /**
     * Set resource
     *
     * @param string $resource
     * @return $this
     */
    public function setResource(string $resource): ResourceInterface;

    /**
     * Get resource
     *
     * @return string
     */
    public function getResource(): string;

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): ResourceInterface;

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): ResourceInterface;

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Set icon
     *
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon): ResourceInterface;

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon(): string;

    /**
     * Set fields
     *
     * @param \Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldInterface[] $fields
     * @return $this
     */
    public function setFields(array $fields): ResourceInterface;

    /**
     * Get fields
     *
     * @return \Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldInterface[]
     */
    public function getFields(): array;

    /**
     * Set sorting
     *
     * @param \Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\SortingInterface[] $sorting
     * @return $this
     */
    public function setSorting(array $sorting): ResourceInterface;

    /**
     * Get sorting
     *
     * @return \Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\SortingInterface[]
     */
    public function getSorting(): array;

    /**
     * Set view type
     *
     * @param string $viewType
     * @return $this
     */
    public function setViewType(string $viewType): ResourceInterface;

    /**
     * Get view type
     *
     * @return string
     */
    public function getViewType(): string;
}
