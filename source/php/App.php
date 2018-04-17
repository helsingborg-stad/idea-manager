<?php

namespace IdeaManager;

use Philo\Blade\Blade as Blade;

class App
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'script'), 5);
        add_action('plugins_loaded', function () {
            if (class_exists('\\ModularityFormBuilder\\PostType')) {
                new PostTypes\Idea();
                new Shortcode();
            }
        });
    }

    /**
     * Enqueue scripts and styles for front ui
     * @return void
     */
    public function script()
    {
        global $post;

        wp_register_script('idea-manager', IDEAMANAGER_URL . '/dist/js/idea-manager.min.js', array('jQuery'), '', true);

        if (is_object($post) && $post->post_type == 'idea' && is_singular('idea')) {
            wp_enqueue_script('idea-manager');
            wp_enqueue_style('idea-manager', IDEAMANAGER_URL . '/dist/css/idea-manager.min.css');

            if (defined('G_GEOCODE_KEY') && G_GEOCODE_KEY) {
                wp_enqueue_script('google-maps-api', '//maps.googleapis.com/maps/api/js?key=' . G_GEOCODE_KEY . '', array(), '', true);
            }
        }
    }

    /**
     * Return markup from a Blade template
     * @param  string $view View name
     * @param  array  $data View data
     * @return string       The markup
     */
    public static function blade($view, $data = array())
    {
        if (!file_exists(IDEAMANAGER_CACHE_DIR)) {
            mkdir(IDEAMANAGER_CACHE_DIR, 0777, true);
        }

        $paths = array(
            IDEAMANAGER_VIEW_PATH,
            get_template_directory() . '/views',
        );

        $blade = new Blade($paths, IDEAMANAGER_CACHE_DIR);
        return $blade->view()->make($view, $data)->render();
    }
}
