<?php
declare(strict_types=1);
namespace JtCron\Model;

use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 * @package JtCron\Model
 */
class Config
{
    const CONFIG_FILE = JTCRON_FOLDER.DIRECTORY_SEPARATOR.'etc'.DIRECTORY_SEPARATOR.'config.yaml';

    /**
     * @var null|array
     */
    protected $_config = null;

    /**
     * @var null|array
     */
    protected $_publicConfig = null;

    /**
     * @return array
     */
    public function getMainConfig() : array
    {
        if ($this->_config === null) {
            try {
                $this->_config = Yaml::parseFile(static::CONFIG_FILE);
            } catch (\Exception $e) {
                $this->_config = [];
                \JtCron\Model\App::create()->log($e->getMessage());
            }
        }
        return $this->_config;
    }

    /**
     * @return array
     */
    public function getPublicConfig() : array
    {
        if ($this->_publicConfig === null) {
            try {
                $this->_publicConfig = Yaml::parseFile($this->_getPublicConfigFile());
            } catch (\Exception $e) {
                \JtCron\Model\App::create()->log($e->getMessage());
                $this->_publicConfig = [];
            }
        }
        return $this->_publicConfig;
    }

    /**
     * @return string
     */
    protected function _getPublicConfigFile() : string
    {
        $folderName = $this->getMainConfig()['publicconfig']['folder'];
        return WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$folderName.DIRECTORY_SEPARATOR.$this->getMainConfig()['publicconfig']['file'];
    }

    public function getInitPagesConfig() : array
    {
        return $this->getMainConfig()['adminmenu']['pages'];
    }
}