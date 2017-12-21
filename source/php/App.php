<?php

namespace IdeaManager;

class App
{
    public function __construct()
    {
        add_filter('Municipio/blade/view_paths', array($this, 'addTemplatePaths'));
        add_action('wp_enqueue_scripts', array($this, 'script'));
        add_action('plugins_loaded', function () {
            if (class_exists('\\ModularityFormBuilder\\PostType')) {
                new PostTypes\Idea();
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

        if (is_object($post) && $post->post_type == 'idea') {
            wp_enqueue_script('idea-manager', IDEAMANAGER_URL . '/dist/js/idea-manager.min.js', array('jQuery'), '', true);

            if (defined('G_GEOCODE_KEY') && G_GEOCODE_KEY) {
                wp_enqueue_script('google-maps-api', '//maps.googleapis.com/maps/api/js?key=' . G_GEOCODE_KEY . '', array(), '', true);
            }
        }
    }

    /**
     * Add searchable blade template paths
     * @param array  $array Template paths
     * @return array        Modified template paths
     */
    public function addTemplatePaths($array)
    {
        $array[] = IDEAMANAGER_TEMPLATE_PATH;
        return $array;
    }
}
