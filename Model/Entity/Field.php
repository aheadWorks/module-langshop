<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\DataObject;

/**
 * @method $this setCode(string $code)
 * @method $this setLabel(string $label)
 * @method $this setType(string $type)
 * @method $this setIsTranslatable(bool $isTranslatable)
 * @method $this setIsFilterable(bool $isFilterable)
 * @method $this setIsSortable(bool $isSortable)
 * @method $this setIsTitle(bool $isTitle)
 * @method $this setFilterType(string $filterType)
 * @method $this setFilterOptions(OptionSourceInterface $filterOptions)
 * @method $this setSortOrder(int $sortOrder)
 * @method $this setVisibleOn(string[] $visibleOn)
 *
 * @method string getCode()
 * @method string getLabel()
 * @method string getType()
 * @method bool getIsTranslatable()
 * @method bool getIsFilterable()
 * @method bool getIsSortable()
 * @method bool getIsTitle()
 * @method string getFilterType()
 * @method OptionSourceInterface|null getFilterOptions()
 * @method int getSortOrder()
 * @method string[] getVisibleOn()
 */
class Field extends DataObject
{
    /**
     * @param string $code
     * @param string $label
     * @param string $type
     * @param bool $isTranslatable
     * @param bool $isFilterable
     * @param bool $isSortable
     * @param bool $isTitle
     * @param string $filterType
     * @param OptionSourceInterface|null $filterOptions
     * @param int $sortOrder
     * @param array $visibleOn
     * @param array $data
     */
    public function __construct(
        string $code = '',
        string $label = '',
        string $type = 'text',
        bool $isTranslatable = false,
        bool $isFilterable = false,
        bool $isSortable = false,
        bool $isTitle = false,
        string $filterType = 'text',
        OptionSourceInterface $filterOptions = null,
        int $sortOrder = 0,
        array $visibleOn = [],
        array $data = []
    ) {
        $this
            ->setCode($code)
            ->setLabel($label)
            ->setType($type)
            ->setIsTranslatable($isTranslatable)
            ->setIsFilterable($isFilterable)
            ->setIsSortable($isSortable)
            ->setIsTitle($isTitle)
            ->setFilterType($filterType)
            ->setSortOrder($sortOrder)
            ->setVisibleOn($visibleOn);

        if ($filterOptions) {
            $this->setFilterOptions($filterOptions);
        }

        if ($data) {
            parent::__construct($data);
        }
    }
}
