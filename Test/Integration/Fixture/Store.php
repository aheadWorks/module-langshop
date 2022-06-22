<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Integration\Fixture;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ResourceModel\Store as StoreResource;
use Magento\Store\Model\StoreFactory;

class Store
{
    /**
     * @var StoreResource
     */
    private StoreResource $storeResource;

    /**
     * @var StoreFactory
     */
    private StoreFactory $storeFactory;

    /**
     * @var Random
     */
    private Random $random;

    /**
     * @param StoreResource $storeResource
     * @param StoreFactory $storeFactory
     * @param Random $random
     */
    public function __construct(
        StoreResource $storeResource,
        StoreFactory $storeFactory,
        Random $random
    ) {
        $this->storeResource = $storeResource;
        $this->storeFactory = $storeFactory;
        $this->random = $random;
    }

    /**
     * Creates a store
     *
     * @return StoreInterface
     * @throws LocalizedException
     */
    public function create(): StoreInterface
    {
        $storeCode = $this->random->getRandomString(10, Random::CHARS_LOWERS);
        $storeName = $this->random->getRandomString(10);

        $store = $this->storeFactory->create()
            ->setCode($storeCode)
            ->setName($storeName);

        $this->storeResource->save($store);

        return $store;
    }
}
