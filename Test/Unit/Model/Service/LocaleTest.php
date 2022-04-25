<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Unit\Model\Service;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Model\Locale\LoadHandler;
use Aheadworks\Langshop\Model\Locale\SaveHandler;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as ScopeRecordRepository;
use Aheadworks\Langshop\Model\Service\Locale as LocaleService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LocaleTest extends TestCase
{
    /**
     * @var LocaleService
     */
    private $localeService;

    /**
     * @var ScopeRecordRepository|MockObject
     */
    private $scopeRecordRepositoryMock;

    /**
     * @var SaveHandler|MockObject
     */
    private $saveHandlerMock;

    /**
     * @var LoadHandler|MockObject
     */
    private $loadHandlerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->scopeRecordRepositoryMock = $this->createMock(ScopeRecordRepository::class);
        $this->saveHandlerMock = $this->createMock(SaveHandler::class);
        $this->loadHandlerMock = $this->createMock(LoadHandler::class);

        $this->localeService = new LocaleService(
            $this->scopeRecordRepositoryMock,
            $this->saveHandlerMock,
            $this->loadHandlerMock
        );
    }

    public function testGetList()
    {
        $scopeRecord = $this->createMock(RecordInterface::class);
        $locale = $this->createMock(LocaleInterface::class);
        $scopeRecords = [$scopeRecord];
        $locales = [$locale];

        $this->scopeRecordRepositoryMock
            ->expects($this->once())
            ->method('getList')
            ->willReturn($scopeRecords);

        foreach ($scopeRecords as $scopeRecord) {
            $this->loadHandlerMock
                ->expects($this->any())
                ->method('load')
                ->with($scopeRecord)
                ->willReturn($locale);
        }

        $this->assertSame($locales, $this->localeService->getList());
    }
}
