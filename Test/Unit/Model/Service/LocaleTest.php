<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Unit\Model\Service;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Model\Locale\LoadHandler;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as ScopeRecordRepository;
use Aheadworks\Langshop\Model\Service\Locale as LocaleService;
use Magento\Framework\Webapi\Exception as WebapiException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LocaleTest extends TestCase
{
    /**
     * @var LocaleService
     */
    private LocaleService $localeService;

    /**
     * @var ScopeRecordRepository|MockObject
     */
    private $scopeRecordRepositoryMock;

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
        $this->loadHandlerMock = $this->createMock(LoadHandler::class);

        $this->localeService = new LocaleService(
            $this->scopeRecordRepositoryMock,
            $this->loadHandlerMock
        );
    }

    /**
     * Test 'getList' method
     *
     * @return void
     * @throws WebapiException
     */
    public function testGetList(): void
    {
        $scopeRecord = $this->createMock(RecordInterface::class);
        $locale = $this->createMock(LocaleInterface::class);

        $this->scopeRecordRepositoryMock
            ->expects($this->once())
            ->method('getList')
            ->willReturn([$scopeRecord]);

        $this->scopeRecordRepositoryMock
            ->expects($this->once())
            ->method('getPrimary')
            ->willReturn($scopeRecord);

        $this->loadHandlerMock
            ->expects($this->any())
            ->method('load')
            ->with($scopeRecord)
            ->willReturn($locale);

        $this->assertSame([$locale], $this->localeService->getList());
    }
}
