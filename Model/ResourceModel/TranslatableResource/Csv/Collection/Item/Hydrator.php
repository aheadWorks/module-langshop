<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection\Item;

use Aheadworks\Langshop\Model\Csv\File\Reader as CsvReader;
use Aheadworks\Langshop\Model\Csv\Model as CsvModel;
use Aheadworks\Langshop\Model\Source\CsvFile;
use Psr\Log\LoggerInterface;
use Exception;

class Hydrator
{
    /**
     * @var CsvReader
     */
    private CsvReader $csvReader;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param CsvReader $csvReader
     * @param LoggerInterface $logger
     */
    public function __construct(
        CsvReader $csvReader,
        LoggerInterface $logger
    ) {
        $this->csvReader = $csvReader;
        $this->logger = $logger;
    }

    /**
     * Fill the model with CSV file data
     *
     * @param CsvModel $csvModel
     * @param string $packageName
     * @param string $defaultLocaleCode
     * @param string $storeLocaleCode
     * @param array $translationData
     * @return CsvModel
     */
    public function fillWithData(
        CsvModel $csvModel,
        string $packageName,
        string $defaultLocaleCode,
        string $storeLocaleCode,
        array $translationData
    ): CsvModel {
        $names = explode('_', $packageName);
        $csvModel
            ->setId($packageName)
            ->setVendorName($names[0] ?? '')
            ->setModuleName($names[1] ?? '')
            ->setLines(
                $this->getLinesData(
                    $packageName,
                    $defaultLocaleCode,
                    $storeLocaleCode,
                    $translationData
                )
            );

        return $csvModel;
    }

    /**
     * Retrieve prepared data array for CSV file lines translation
     *
     * @param string $packageName
     * @param string $defaultLocaleCode
     * @param string $storeLocaleCode
     * @param array $translationData
     * @return array
     */
    private function getLinesData(
        string $packageName,
        string $defaultLocaleCode,
        string $storeLocaleCode,
        array $translationData
    ): array {
        try {
            $data = $this->csvReader->getCsvData($packageName, $defaultLocaleCode);
            return $this->getTranslationLines(
                $this->getOriginalLines($data),
                $translationData,
                $storeLocaleCode,
                $defaultLocaleCode
            );
        } catch (Exception $exception) {
            $this->logger->error(
                __("Impossible to fetch CSV file data for the package %1, error: %2",
                    $packageName,
                    $exception->getMessage()
                )
            );
            return [];
        }
    }

    /**
     * Get translation lines
     *
     * @param string[] $lines
     * @param array $translationData
     * @param string $localeCode
     * @param string $defaultLocaleCode
     * @return string[]
     */
    private function getTranslationLines(
        array $lines,
        array $translationData,
        string $localeCode,
        string $defaultLocaleCode
    ): array {
        $result = [];
        foreach ($lines as $line) {
            $result[$line] = $localeCode === $defaultLocaleCode ? $line : '';

            foreach ($translationData as $translationValue) {
                if (isset($translationValue[$line])) {
                    $result[$line] = $translationValue[$line];
                }
            }
        }

        return $result;
    }

    /**
     * Get original lines
     *
     * @param array $csvData
     * @return array
     */
    private function getOriginalLines(array $csvData): array
    {
        $result = [];
        foreach ($csvData as $data) {
            $originalString = $data[CsvFile::ORIGINAL_INDEX];
            if ($originalString && strlen($originalString) < 256) {
                $result[] = $originalString;
            }
        }

        return $result;
    }
}
