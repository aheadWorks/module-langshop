<?php
namespace Aheadworks\Langshop\Model\Config\Backend\Locale\Scope\ListToTranslate;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface as LocaleScopeRecordInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Generator as LocaleScopeRecordGenerator;
use Aheadworks\Langshop\Model\Locale\Scope\UidResolver as LocaleScopeUidResolver;

class Converter
{
    /**
     * Separator between UIDs for the serialized list
     */
    const SCOPE_UID_SEPARATOR = ',';

    /**
     * @var LocaleScopeUidResolver
     */
    private $localeScopeUidResolver;

    /**
     * @var LocaleScopeRecordGenerator
     */
    private $localeScopeRecordGenerator;

    /**
     * @param LocaleScopeUidResolver $localeScopeUidResolver
     * @param LocaleScopeRecordGenerator $localeScopeRecordGenerator
     */
    public function __construct(
        LocaleScopeUidResolver $localeScopeUidResolver,
        LocaleScopeRecordGenerator $localeScopeRecordGenerator
    ) {
        $this->localeScopeUidResolver = $localeScopeUidResolver;
        $this->localeScopeRecordGenerator = $localeScopeRecordGenerator;
    }

    /**
     * Convert locale scope record list to the serialized scope UID list
     *
     * @param LocaleScopeRecordInterface[] $localeScopeRecordList
     * @return string
     */
    public function fromLocaleScopeRecordListToSerializedScopeUidList($localeScopeRecordList)
    {
        $scopeUidList = [];
        foreach ($localeScopeRecordList as $localeScopeRecord) {
            $scopeUidList[] = $this->localeScopeUidResolver->getUid(
                $localeScopeRecord->getScopeType(),
                $localeScopeRecord->getScopeId()
            );
        }

        return implode(self::SCOPE_UID_SEPARATOR, $scopeUidList);
    }

    /**
     * Convert serialized scope UID list to the locale scope record list
     *
     * @param string $serializedScopeUidList
     * @return LocaleScopeRecordInterface[]
     */
    public function fromSerializedScopeUidListToLocaleScopeRecordList($serializedScopeUidList)
    {
        $localeScopeRecordList = [];
        $scopeUidList = explode(self::SCOPE_UID_SEPARATOR, $serializedScopeUidList);
        foreach ($scopeUidList as $scopeUid) {
            $scopeId = $this->localeScopeUidResolver->getScopeIdByUid($scopeUid);
            $scopeType = $this->localeScopeUidResolver->getScopeTypeByUid($scopeUid);
            $localeScopeRecordList[] = $this->localeScopeRecordGenerator->generateForScope(
                $scopeType,
                $scopeId
            );
        }
        return $localeScopeRecordList;
    }
}
