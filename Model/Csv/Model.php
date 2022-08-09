<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\Csv;

use Magento\Framework\DataObject;

class Model extends DataObject
{
    public const ID = 'id';
    public const MODULE_NAME = 'module_name';
    public const VENDOR_NAME = 'vendor_name';
    public const LINES = 'lines';

    /**
     * Set id
     *
     * @param string $name
     * @return Model
     */
    public function setId(string $name): Model
    {
        return $this->setData(self::ID, $name);
    }

    /**
     * Get id
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->getData(self::ID);
    }

    /**
     * Set module name
     *
     * @param string $name
     * @return Model
     */
    public function setModuleName(string $name): Model
    {
        return $this->setData(self::MODULE_NAME, $name);
    }

    /**
     * Get module name
     *
     * @return string|null
     */
    public function getModuleName(): ?string
    {
        return $this->getData(self::MODULE_NAME);
    }

    /**
     * Set vendor name
     *
     * @param string $name
     * @return Model
     */
    public function setVendorName(string $name): Model
    {
        return $this->setData(self::VENDOR_NAME, $name);
    }

    /**
     * Get vendor name
     *
     * @return string|null
     */
    public function getVendorName(): ?string
    {
        return $this->getData(self::VENDOR_NAME);
    }

    /**
     * Set lines
     *
     * @param array $lines
     * @return Model
     */
    public function setLines(array $lines): Model
    {
        return $this->setData(self::LINES, $lines);
    }

    /**
     * Get lines
     *
     * @return array
     */
    public function getLines(): array
    {
        return $this->getData(self::LINES) ?? [];
    }
}
