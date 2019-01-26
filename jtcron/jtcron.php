<?php
/**
 * Plugin Name: Jtcron
 * Plugin URI: https://github.com/jenyatrishin/jtcron
 * Description: Cron job runner
 * Version: 1.0.1
 * Author: Evgeniy Trishin
 * Author URI: https://www.linkedin.com/in/yevhenii-trishyn-47a115137/
 * License: GPLv2 or later
 */

define('JTCRON_FOLDER', __DIR__);
define('JTCRON_ROOT_NAMESPACE', 'JtCron');
define('JTCRON_APP_FOLDER', 'app');
define('JTCRON_URL', plugin_dir_url(__FILE__));

require JTCRON_FOLDER.'/app/autoloader.php';
require JTCRON_FOLDER.'/vendor/autoload.php';
require_once( ABSPATH.'wp-admin/includes/upgrade.php' );

$app = \JtCron\Model\App::create();

register_activation_hook( __FILE__, [$app, 'activate' ] );

add_action('rest_api_init', [$app, 'registerRestRoutes']);

add_action('admin_menu', [$app, 'registerAdminPages']);

add_action('admin_enqueue_scripts', [$app, 'registerAdminAssets']);