<?php
namespace Aheadworks\Langshop\Model\Locale\Scope;

class UidResolver
{
    /**
     * Separator if the UID between scope type and its id
     */
    const SEPARATOR = '_';

    /**
     * Get UID for the scope of the given type
     *
     * @param string $scopeType
     * @param int $scopeId
     * @return string
     */
    public function getUid($scopeType, $scopeId)
    {
        return implode(
            self::SEPARATOR,
            [
                $scopeType,
                $scopeId
            ]
        );
    }

    /**
     * Retrieve scope type by its UID
     *
     * @param string $scopeUid
     * @return string
     * TODO: consider throwing exceptions in case if the UID is incorrect
     */
    public function getScopeTypeByUid($scopeUid)
    {
        $scopeParameterList = explode(self::SEPARATOR, $scopeUid);
        return $scopeParameterList[0] ?? '';
    }

    /**
     * Retrieve scope id by its UID
     *
     * @param string $scopeUid
     * @return string
     * TODO: consider throwing exceptions in case if the UID is incorrect
     */
    public function getScopeIdByUid($scopeUid)
    {
        $scopeParameterList = explode(self::SEPARATOR, $scopeUid);
        return $scopeParameterList[1] ?? '';
    }
}
