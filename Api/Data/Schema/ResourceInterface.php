<?php
namespace Aheadworks\Langshop\Api\Data\Schema;

interface ResourceInterface
{
    public const RESOURCE = 'resource';
    public const LABEL = 'label';
    public const DESCRIPTION = 'description';
    public const FIELDS = 'fields';
    public const SORTING = 'sorting';
    public const VIEW_TYPE = 'viewType';

    /**
     * Set resource
     *
     * @param string $resource
     * @return $this
     */
    public function setResource($resource);

    /**
     * Get resource
     *
     * @return string
     */
    public function getResource();

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label);

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set fields
     *
     * @param \Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldInterface[] $fields
     * @return $this
     */
    public function setFields($fields);

    /**
     * Get fields
     *
     * @return \Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldInterface[]
     */
    public function getFields();

    /**
     * Set sorting
     *
     * @param \Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\SortingInterface[] $sorting
     * @return $this
     */
    public function setSorting(array $sorting);

    /**
     * Get sorting
     *
     * @return \Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\SortingInterface[]
     */
    public function getSorting();

    /**
     * Set view type
     *
     * @param string $viewType
     * @return $this
     */
    public function setViewType($viewType);

    /**
     * Get view type
     *
     * @return string
     */
    public function getViewType();
}
