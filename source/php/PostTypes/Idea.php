<?php

namespace IdeaManager\PostTypes;

class Idea extends \ModularityFormBuilder\PostType
{
    public $nameSingular;
    public $namePlural;

    public function __construct()
    {
        $this->nameSingular = __('Idea', 'idea-manager');
        $this->namePlural   = __('Ideas', 'idea-manager');
        $this->postTypeSlug = 'idea';

        parent::__construct();

        add_action('init', array($this, 'register'));
        $this->taxonomyStatus();
        $this->taxonomyAdministrationUnit();
        add_action('save_post_' . $this->postTypeSlug, array($this, 'setDefaultData'));

        remove_action('plugins_loaded', array($this, 'formFilter'), 11);
    }

    /**
     * Registers Idea post type
     * @return void
     */
    public function register()
    {
        $labels = array(
            'name'                => $this->nameSingular,
            'singular_name'       => $this->nameSingular,
            'add_new'             => sprintf(__('Add new %s', 'idea-manager'), $this->nameSingular),
            'add_new_item'        => sprintf(__('Add new %s', 'idea-manager'), $this->nameSingular),
            'edit_item'           => sprintf(__('Edit %s', 'idea-manager'), $this->nameSingular),
            'new_item'            => sprintf(__('New %s', 'idea-manager'), $this->nameSingular),
            'view_item'           => sprintf(__('View %s', 'idea-manager'), $this->nameSingular),
            'search_items'        => sprintf(__('Search %s', 'idea-manager'), $this->namePlural),
            'not_found'           => sprintf(__('No %s found', 'idea-manager'), $this->namePlural),
            'not_found_in_trash'  => sprintf(__('No %s found in trash', 'idea-manager'), $this->namePlural),
            'parent_item_colon'   => sprintf(__('Parent %s:', 'idea-manager'), $this->nameSingular),
            'menu_name'           => $this->namePlural,
        );

        $args = array(
            'labels'              => $labels,
            'hierarchical'        => false,
            'description'         => 'Modularity Form Builder Ideas',
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => false,
            'menu_position'       => 500,
            'menu_icon'           => 'dashicons-lightbulb',
            'show_in_nav_menus'   => false,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'has_archive'         => true,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => false,
            'capability_type'     => 'post',
            'capabilities' => array(
                'create_posts'       => 'do_not_allow',
            ),
            'map_meta_cap'        => true,
            'supports'            => array('title', 'author')
        );

        register_post_type($this->postTypeSlug, $args);
    }

    /**
     * Create status taxonomy
     * @return string
     */
    public function taxonomyStatus()
    {
        // Register new taxonomy
        $taxonomyStatus = new \ModularityFormBuilder\Entity\Taxonomy(
            __('Status', 'idea-manager'),
            __('Statuses', 'idea-manager'),
            'idea_statuses',
            array($this->postTypeSlug),
            array(
                'hierarchical'      => false,
                'public'            => true,
                'show_ui'           => true,
                'show_in_nav_menus' => true,
                '_builtin'          => false,
            )
        );

        // Remove deafult UI
        add_action('admin_menu', function () {
            remove_meta_box('tagsdiv-idea_statuses', $this->postTypeSlug, 'side');
        });

        //Add filter
        new \ModularityFormBuilder\Entity\Filter(
            $taxonomyStatus->slug,
            $this->postTypeSlug
        );
    }

    /**
     * Create administration unit taxonomy
     * @return string
     */
    public function taxonomyAdministrationUnit()
    {
        // Register new taxonomy
        $taxonomyAdminUnit = new \ModularityFormBuilder\Entity\Taxonomy(
            __('Administration unit', 'idea-manager'),
            __('Administration units', 'idea-manager'),
            'idea_administration_units',
            array($this->postTypeSlug),
            array(
                'hierarchical'      => false,
                'public'            => false,
                'show_ui'           => false,
                'show_in_nav_menus' => false,
                '_builtin'          => false,
            )
        );

        // Remove deafult UI
        add_action('admin_menu', function () {
            remove_meta_box('tagsdiv-idea_administration_units', $this->postTypeSlug, 'side');
        });

        //Add filter
        new \ModularityFormBuilder\Entity\Filter(
            $taxonomyAdminUnit->slug,
            $this->postTypeSlug
        );
    }

    public function setDefaultData($postId)
    {
        $currentUser = wp_get_current_user();
        $post = get_post($postId);

        if ($post->post_date == $post->post_modified) {
            // Set default status
            $terms = wp_set_object_terms($postId, 'Ej läst', 'idea_statuses');

            // @TODO Save Users Administration unit
            // if (class_exists('\\Intranet\\User\\AdministrationUnits')) {
            // }
        }
    }

    /**
     * Table columns
     * @param  array $columns
     * @return array
     */
    public function tableColumns($columns)
    {
        return array(
            'cb' => '',
            'title' => __('Title'),
            'date' => __('Date')
        );
    }

}
