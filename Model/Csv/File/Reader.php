<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\Csv\File;

use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir\Reader as DirReader;

class Reader
{
    /**
     * @var Csv
     */
    private Csv $csvFile;

    /**
     * @var DirReader
     */
    private DirReader $dirReader;

    /**
     * @param Csv $csvFile
     * @param DirReader $dirReader
     */
    public function __construct(
        Csv $csvFile,
        DirReader $dirReader
    ) {
        $this->csvFile = $csvFile;
        $this->dirReader = $dirReader;
    }

    /**
     * Get csv data for specific module and store
     *
     * @param string $moduleName
     * @param string $localeCode
     * @return array
     * @throws \Exception
     */
    public function getCsvData(string $moduleName, string $localeCode = 'en_US'): array
    {
        $dir = $this->dirReader->getModuleDir('i18n', $moduleName) . "/$localeCode.csv";

        return $this->csvFile->getData($dir);
    }
}
