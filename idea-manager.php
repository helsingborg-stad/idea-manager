<?php

/**
 * Plugin Name:       Idea Manager
 * Plugin URI:        https://github.com/helsingborg-stad/idea-manager
 * Description:       Plugin to gather and manage ideas
 * Version:           1.0.0
 * Author:            Jonatan Hanson
 * Author URI:        https://github.com/helsingborg-stad
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       idea-manager
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('IDEAMANAGER_PATH', plugin_dir_path(__FILE__));
define('IDEAMANAGER_URL', plugins_url('', __FILE__));
define('IDEAMANAGER_VIEW_PATH', IDEAMANAGER_PATH . 'views/');
define('IDEAMANAGER_CACHE_DIR', trailingslashit(wp_upload_dir()['basedir']) . 'cache/blade-cache/');

load_plugin_textdomain('idea-manager', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once IDEAMANAGER_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once IDEAMANAGER_PATH . 'Public.php';
if (file_exists(IDEAMANAGER_PATH . 'vendor/autoload.php')) {
    require_once IDEAMANAGER_PATH . 'vendor/autoload.php';
}

// Acf auto import and export
add_action('plugins_loaded', function () {
    $acfExportManager = new \AcfExportManager\AcfExportManager();
    $acfExportManager->setTextdomain('idea-manager');
    $acfExportManager->setExportFolder(IDEAMANAGER_PATH . 'acf-fields/');
    $acfExportManager->autoExport(array(
        'idea_status' => 'group_5a134a48de846',
		'administration_unit' => 'group_5a134bb83af1a',
        'tax_color' => 'group_5ab3a45759ba5'
    ));
    $acfExportManager->import();
});

// Instantiate and register the autoloader
$loader = new IdeaManager\Vendor\Psr4ClassLoader();
$loader->addPrefix('IdeaManager', IDEAMANAGER_PATH);
$loader->addPrefix('IdeaManager', IDEAMANAGER_PATH . 'source/php/');
$loader->register();

// Start application
new IdeaManager\App();
