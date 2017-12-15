<?php

namespace IdeaManager;

class App
{
    public function __construct()
    {
        add_filter('Municipio/blade/view_paths', array($this, 'addTemplatePaths'));

        add_action('plugins_loaded', function () {
            if (class_exists('\\ModularityFormBuilder\\PostType')) {
                new PostTypes\Idea();
            }
        });
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
