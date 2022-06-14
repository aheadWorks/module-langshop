<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Integration\Model\Service;

use Aheadworks\Langshop\Model\Service\Locale as LocaleService;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Webapi\Exception;
use PHPUnit\Framework\TestCase;

class LocaleTest extends TestCase
{
    /**
     * @var LocaleService|null
     */
    private ?LocaleService $localeService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = ObjectManager::getInstance();
        $this->localeService = $objectManager->create(LocaleService::class);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testGetList(): void
    {
        $locales = $this->localeService->getList();

        $this->assertNotEmpty($locales);
    }
}
