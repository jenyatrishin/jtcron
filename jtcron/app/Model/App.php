<?php
declare(strict_types=1);
namespace JtCron\Model;

use JtCron\Setup\Installer;
use JtCron\Controller\Rest\CronjobController;

/**
 * Class App
 * @package JtCron\Model
 */
class App
{

    /**
     * @var null|App
     */
    private static $_instance = null;

    /**
     * @var Config
     */
    protected $_config;

    /**
     * @var Installer
     */
    protected $_installer;

    /**
     * @var CronjobController
     */
    protected $_cronjobRestController;

    /**
     * App constructor.
     */
    private function __construct()
    {
        $this->_config = new Config();
        $this->_installer = new Installer();
        $this->_cronjobRestController = new CronjobController();
    }

    /**
     *
     */
    private function __clone(){}

    /**
     * @return App
     */
    public static function create() : self
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @return void
     */
    public function activate() : void
    {
        $this->_installer->install();
    }

    /**
     * @return Config
     */
    public function getConfigModel() : Config
    {
        return $this->_config;
    }

    /**
     * @param $message
     * @return void
     */
    public function log($message) : void
    {
        file_put_contents(JTCRON_FOLDER.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.$this->_config->getMainConfig()['logfile'], $message.PHP_EOL, FILE_APPEND);
    }

    /**
     * @return void
     */
    public function registerRestRoutes() : void
    {
        $this->_cronjobRestController->register_routes();
    }

    /**
     * @return void
     */
    public function registerAdminPages() : void
    {
        $pages = $this->_config->getInitPagesConfig();
        foreach ($pages as $page) {
            $object = new $page['class'];
			if (isset($page['parent'])) {
				add_submenu_page( $page['parent'], $page['pageTitle'], $page['menuTitle'], 'manage_options', $page['menuSlug'], [$object, $page['method']]);	
			} else {
				add_menu_page($page['pageTitle'], $page['menuTitle'], 'manage_options', $page['menuSlug'], [$object, $page['method']]);	
			}
        }
    }

    /**
     * @return void
     */
    public function registerAdminAssets() : void
    {
        wp_enqueue_script('jtcron_js', JTCRON_URL . 'pub/assets/dist/js/bundle.js', [], '1.0.0', true);
        wp_enqueue_style( 'jtcron_css', JTCRON_URL . 'pub/assets/dist/css/screen.css' );
    }
}