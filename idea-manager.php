<?php

/**
 * Plugin Name:       Idea manager
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
define('IDEAMANAGER_TEMPLATE_PATH', IDEAMANAGER_PATH . 'templates/');

load_plugin_textdomain('idea-manager', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once IDEAMANAGER_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once IDEAMANAGER_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new IdeaManager\Vendor\Psr4ClassLoader();
$loader->addPrefix('IdeaManager', IDEAMANAGER_PATH);
$loader->addPrefix('IdeaManager', IDEAMANAGER_PATH . 'source/php/');
$loader->register();

// Start application
new IdeaManager\App();
