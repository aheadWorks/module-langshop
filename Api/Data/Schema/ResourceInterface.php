<?php
namespace Aheadworks\Langshop\Api\Data\Schema;

use Aheadworks\Langshop\Api\Data\Schema\Resource\FieldInterface;
use Aheadworks\Langshop\Api\Data\Schema\Resource\SortingInterface;

interface ResourceInterface
{
    const RESOURCE = 'resource';
    const LABEL = 'label';
    const DESCRIPTION = 'description';
    const FIELDS = 'fields';
    const SORTING = 'sorting';
    const VIEW_TYPE = 'viewType';

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
     * @param FieldInterface[] $fields
     * @return $this
     */
    public function setFields($fields);

    /**
     * Get fields
     *
     * @return FieldInterface[]
     */
    public function getFields();

    /**
     * Set sorting
     *
     * @param SortingInterface[] $sorting
     * @return $this
     */
    public function setSorting(array $sorting);

    /**
     * Get sorting
     *
     * @return SortingInterface[]
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
