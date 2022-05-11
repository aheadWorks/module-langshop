<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\Schema\TranslatableResource;

interface SortingInterface
{
    public const KEY = 'key';
    public const LABEL = 'label';
    public const FIELD = 'field';
    public const DIRECTION = 'direction';

    /**
     * Set key
     *
     * @param string $key
     * @return $this
     */
    public function setKey($key);

    /**
     * Get key
     *
     * @return string
     */
    public function getKey();

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
}
