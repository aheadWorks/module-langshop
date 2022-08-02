<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model;

use Magento\Framework\Translate;

class Translation extends Translate
{
    private const MODULE_TRANSLATION = 'module';
    private const DB_TRANSLATION = 'db';

    /**
     * Load data from module translation files by list of modules
     *
     * @param array $modules
     * @return $this
     */
    protected function loadModuleTranslationByModulesList(array $modules)
    {
        foreach ($modules as $module) {
            $moduleFilePath = $this->_getModuleTranslationFile($module, $this->getLocale());
            $this->addData(self::MODULE_TRANSLATION, $this->_getFileData($moduleFilePath));
        }
        return $this;
    }

    /**
     * Loading current translation from DB
     *
     * @return $this
     */
    protected function _loadDbTranslation()
    {
        $data = $this->_translateResource->getTranslationArray(null, $this->getLocale());
        $this->addData(self::DB_TRANSLATION, array_map('htmlspecialchars_decode', $data));
        return $this;
    }

    /**
     * Adding translation data
     *
     * @param string $translationType
     * @param array $data
     * @return $this
     */
    protected function addData(string $translationType, array $data = []): Translation
    {
        foreach ($data as $key => $value) {
            if ($key === $value) {
                if (isset($this->_data[$translationType][$key])) {
                    unset($this->_data[$translationType][$key]);
                }
                continue;
            }

            $value = is_array($value) ? $value : (string) $value;
            $key = str_replace('""', '"', $key);
            $value = str_replace('""', '"', $value);

            $this->_data[$translationType][$key] = $value;
        }
        return $this;
    }
}
