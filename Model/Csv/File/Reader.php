<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\Csv\File;

use Aheadworks\Langshop\Model\Config\Locale as LocaleConfig;
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
     * @var LocaleConfig
     */
    private LocaleConfig $localeConfig;

    /**
     * @param Csv $csvFile
     * @param DirReader $dirReader
     * @param LocaleConfig $localeConfig
     */
    public function __construct(
        Csv $csvFile,
        DirReader $dirReader,
        LocaleConfig $localeConfig
    ) {
        $this->csvFile = $csvFile;
        $this->dirReader = $dirReader;
        $this->localeConfig = $localeConfig;
    }

    /**
     * Get csv data for specific module and store
     *
     * @param string $moduleName
     * @param int $storeId
     * @return array
     * @throws \Exception
     */
    public function getCsvData(string $moduleName, int $storeId = 0): array
    {
        $locale = $this->localeConfig->getValue($storeId);
        $dir = $this->dirReader->getModuleDir('i18n', $moduleName) . "/$locale.csv";

        return $this->csvFile->getData($dir);
    }
}
