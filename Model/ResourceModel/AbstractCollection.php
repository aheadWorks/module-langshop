<?php
namespace Aheadworks\Langshop\Model\ResourceModel;

use Magento\Framework\DataObject;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Model\AbstractModel;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection as FrameworkAbstractCollection;
use Aheadworks\Langshop\Model\ResourceModel\Collection\ModifierInterface
    as CollectionModifierInterface;

//TODO: consider adding standard methods `attachRelationTable` and `joinLinkageTable`, only if really necessary
abstract class AbstractCollection extends FrameworkAbstractCollection
{
    /**
     * @var CollectionModifierInterface
     */
    protected $modifier;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param CollectionModifierInterface $modifier
     * @param AdapterInterface $connection
     * @param AbstractDb $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        CollectionModifierInterface $modifier,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->modifier = $modifier;
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterLoad()
    {
        $this->modifyItemsData();
        $this->unserializeItemsFields();
        return parent::_afterLoad();
    }

    /**
     * Modify data for loaded collection items
     *
     * @return $this
     */
    protected function modifyItemsData()
    {
        /** @var DataObject $item */
        foreach ($this as $item) {
            $item = $this->modifier->modifyData($item);
        }
        return $this;
    }

    /**
     * Unserialize fields for loaded collection items
     *
     * @return $this
     */
    protected function unserializeItemsFields()
    {
        /** @var DataObject $item */
        foreach ($this as $item) {
            if ($item instanceof AbstractModel
                && $this->getResource() instanceof AbstractDb
            ) {
                $this->getResource()->unserializeFields($item);
            }
        }
        return $this;
    }
}
