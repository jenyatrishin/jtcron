<?php
declare(strict_types=1);
namespace JtCron\Setup;

use JtCron\Model\Config;

/**
 * Class Installer
 * @package JtCron\Setup
 */
class Installer
{

    const TABLE_NAME = 'cronjob';

    /**
     * @var Config
     */
    protected $_config;

    /**
     * @var \wpdb
     */
    protected $_db;

    /**
     * Installer constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->_config = new Config();
        $this->_db = $wpdb;
    }

    /**
     * @return void
     */
    public function install() : void
    {
        $this->_copyShellFile();
        $this->_copyConfigFile();
        $this->_createCronTable();
    }

    /**
     * @return void
     */
    protected function _copyShellFile() : void
    {
        $mainConfig = $this->_config->getMainConfig();
        $fileName = JTCRON_FOLDER.DIRECTORY_SEPARATOR.'pub'.DIRECTORY_SEPARATOR.$mainConfig['shellfile'];
        $destination = ABSPATH.$mainConfig['shellfile'];
        if (!file_exists($destination)) {
            copy($fileName, $destination);
        }
    }

    /**
     * @return void
     */
    protected function _copyConfigFile() : void
    {
        $mainConfig = $this->_config->getMainConfig();
        $folderName = $mainConfig['publicconfig']['folder'];
        mkdir(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$folderName);
        $fileName = JTCRON_FOLDER.DIRECTORY_SEPARATOR.'pub'.DIRECTORY_SEPARATOR.$mainConfig['publicconfig']['file'];
        $destination = WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$folderName.DIRECTORY_SEPARATOR.$mainConfig['publicconfig']['file'];
        copy($fileName, $destination);
    }

    /**
     * @return void
     */
    protected function _createCronTable() : void
    {
        $charset_collate = $this->_db->get_charset_collate();
        $table = $this->_db->prefix.self::TABLE_NAME;
        $sql = "CREATE TABLE IF NOT EXISTS ".$table." (
          id int(11) NOT NULL AUTO_INCREMENT,
          time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
          name varchar(255) NOT NULL,
          message text NOT NULL,
          status varchar(255) NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta( $sql );
    }
}