<?php

namespace Aheadworks\Langshop\Test\Unit\Model\ResourceModel\TranslatableResource\Csv\Collection\Item;

use Aheadworks\Langshop\Model\Csv\Model;
use Aheadworks\Langshop\Model\Source\CsvFile;
use PHPUnit\Framework\TestCase;
use Aheadworks\Langshop\Model\Csv\File\Reader as CsvReader;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Csv\Collection\Item\Hydrator;

class HydratorTest extends TestCase
{
    /**
     * @var CsvReader|MockObject
     */
    private $csvReaderMock;

    /**
     * @var LoggerInterface|MockObject
     */
    private $loggerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->csvReaderMock = $this->createMock(CsvReader::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->hydrator = new Hydrator(
            $this->csvReaderMock,
            $this->loggerMock
        );
    }

    /**
     * Test 'fillWithData' method
     *
     * @return void
     * @throws \Exception
     */
    public function testFillWithData(): void
    {
        $model = $this->createMock(Model::class);
        $defaultLocale = $locale = 'en_US';
        $packageName = 'Aheadworks_Lanshop';
        $csvData = [[CsvFile::ORIGINAL_INDEX => 'original', CsvFile::TRANSLATION_INDEX => 'translation']];
        $translationData = ['original'=> 'translation'];

        $names = explode('_', $packageName);
        $model
            ->expects($this->once())
            ->method('setId')
            ->with($packageName)
            ->willReturnSelf();
        $model
            ->expects($this->once())
            ->method('setVendorName')
            ->with($names[0])
            ->willReturnSelf();
        $model
            ->expects($this->once())
            ->method('setModuleName')
            ->with($names[1])
            ->willReturnSelf();

        $this->csvReaderMock
            ->expects($this->once())
            ->method('getCsvData')
            ->with($packageName, $locale)
            ->willReturn($csvData);

        $originalLines = $this->getOriginalLines($csvData);
        $lines = $this->getTranslationLines($originalLines, $translationData, $locale);
        $model
            ->expects($this->once())
            ->method('setLines')
            ->with($lines)
            ->willReturnSelf();

        $this->hydrator->fillWithData(
            $model,
            $packageName,
            $defaultLocale,
            $locale,
            $translationData
        );
    }

    /**
     * Get translation lines
     *
     * @param string[] $lines
     * @param array $translationData
     * @param string $localeCode
     * @return string[]
     */
    private function getTranslationLines(array $lines, array $translationData, string $localeCode): array
    {
        $result = [];
        foreach ($lines as $line) {
            $result[$line] = $localeCode === 'en_US' ? $line : '';

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
            $result[] = $data[CsvFile::ORIGINAL_INDEX];
        }

        return $result;
    }
}