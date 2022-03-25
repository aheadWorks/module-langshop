<?php
namespace Aheadworks\Langshop\Test\Unit\Model\Service;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Model\Locale\LoadHandler;
use Aheadworks\Langshop\Model\Locale\SaveHandler;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as ScopeRecordRepository;
use Aheadworks\Langshop\Model\Locale\Scope\Record\SearchResultsInterface;
use Aheadworks\Langshop\Model\Service\Locale as LocaleService;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
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
     * @var SearchCriteriaBuilder|MockObject
     */
    private $searchCriteriaBuilderMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->scopeRecordRepositoryMock = $this->createMock(ScopeRecordRepository::class);
        $this->saveHandlerMock = $this->createMock(SaveHandler::class);
        $this->loadHandlerMock = $this->createMock(LoadHandler::class);
        $this->searchCriteriaBuilderMock = $this->createMock(SearchCriteriaBuilder::class);

        $this->localeService = new LocaleService(
            $this->scopeRecordRepositoryMock,
            $this->saveHandlerMock,
            $this->loadHandlerMock,
            $this->searchCriteriaBuilderMock
        );
    }

    public function testGetList()
    {
        $searchCriteriaMock = $this->createMock(SearchCriteria::class);
        $searchResultMock = $this->createMock(SearchResultsInterface::class);
        $scopeRecord = $this->createMock(RecordInterface::class);
        $locale = $this->createMock(LocaleInterface::class);
        $scopeRecords = [$scopeRecord];
        $locales = [$locale];

        $this->searchCriteriaBuilderMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteriaMock);
        $this->scopeRecordRepositoryMock
            ->expects($this->once())
            ->method('getList')
            ->with($searchCriteriaMock)
            ->willReturn($searchResultMock);
        $searchResultMock
            ->expects($this->once())
            ->method('getItems')
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
