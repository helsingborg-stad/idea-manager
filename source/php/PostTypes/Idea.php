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
        $this->taxonomyTags();

        add_action('save_post_' . $this->postTypeSlug, array($this, 'setDefaultData'), 10, 3);
        add_filter('wp_insert_post_data', array($this, 'allowComments'), 99, 2);
        add_filter('dynamic_sidebar_before', array($this, 'contentBeforeSidebar'));
        add_filter('is_active_sidebar', array($this, 'isActiveSidebar'), 11, 2);
        add_filter('ModularityFormBuilder/excluded_fields/front', array($this, 'excludedFields'), 10, 3);
    }

    public function isIdeaPage()
    {
        global $post;

        if (is_object($post) && $post->post_type == $this->postTypeSlug && !is_archive() && !is_admin()) {
            return true;
        }

        return false;
    }

    /**
     * Manually activate right and bottom sidebar to add custom content
     * @param  boolean  $isActiveSidebar Original response
     * @param  string   $sidebar         Sidebar id
     * @return boolean
     */
    public function isActiveSidebar($isActiveSidebar, $sidebar)
    {
        if (($sidebar === 'right-sidebar' || $sidebar === 'bottom-sidebar') && $this->isIdeaPage()) {
            return true;
        }

       return $isActiveSidebar;
    }

    /**
     * Render custom content in sidebar
     * @param string $sidebar
     */
    public function contentBeforeSidebar($sidebar)
    {
        global $post;

        if ($sidebar === 'right-sidebar' && $this->isIdeaPage()) {
            $data = $this->gatherFormData($post);
            $data['showSocial'] = get_field('post_show_share', $post->ID);
            $data['showAuthor'] = get_field('post_show_author', $post->ID) && $post->post_author > 0;
            $data['units'] = !is_wp_error(wp_get_post_terms($post->ID, 'idea_administration_units')) ? wp_get_post_terms($post->ID, 'idea_administration_units') : null;
            $uploadFolder = wp_upload_dir();
            $data['uploadFolder'] = $uploadFolder['baseurl'] . '/modularity-form-builder/';
            $data['profileImage'] = !empty($post->post_author) && get_the_author_meta('user_profile_picture', $post->post_author) ? \Municipio\Helper\Image::resize(get_the_author_meta('user_profile_picture', $post->post_author), 200, 200) : null;

            $this->renderBlade('idea-widgets.blade.php', array(IDEAMANAGER_TEMPLATE_PATH), $data);
        } elseif ($sidebar === 'bottom-sidebar' && $this->isIdeaPage()) {
            $this->renderBlade('idea-mail-modal.blade.php', array(IDEAMANAGER_TEMPLATE_PATH));
        }
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
            'description'         => 'Post type for managing ideas',
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => false,
            'menu_position'       => 50,
            'menu_icon'           => 'dashicons-lightbulb',
            'show_in_nav_menus'   => false,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'has_archive'         => true,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => array(
                'with_front' => false,
                'slug' => $this->postTypeSlug
            ),
            'capability_type'     => 'post',
            'capabilities' => array(
                'create_posts'       => 'do_not_allow',
            ),
            'map_meta_cap'        => true,
            'supports'            => array('title', 'author', 'editor', 'comments', 'thumbnail')
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

        // Remove default UI
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

        // Remove default UI
        add_action('admin_menu', function () {
            remove_meta_box('tagsdiv-idea_administration_units', $this->postTypeSlug, 'side');
        });

        //Add filter
        new \ModularityFormBuilder\Entity\Filter(
            $taxonomyAdminUnit->slug,
            $this->postTypeSlug
        );
    }

    /**
     * Create tags taxonomy
     * @return string
     */
    public function taxonomyTags()
    {
        // Register new taxonomy
        $taxonomyStatus = new \ModularityFormBuilder\Entity\Taxonomy(
            __('Tag', 'idea-manager'),
            __('Tags', 'idea-manager'),
            'idea_tags',
            array($this->postTypeSlug),
            array(
                'hierarchical'      => false,
                'public'            => true,
                'show_ui'           => true,
                'show_in_nav_menus' => true,
                '_builtin'          => false,
            )
        );

        //Add filter
        new \ModularityFormBuilder\Entity\Filter(
            $taxonomyStatus->slug,
            $this->postTypeSlug
        );
    }

    public function setDefaultData($postId, $post, $update)
    {
        $currentUser = wp_get_current_user();

        if (!$update) {
            // Set default status
            wp_set_object_terms($postId, 'Ej läst', 'idea_statuses');
            // Hide Share icons
            update_field('field_56c33d008efe3', false, $postId);
            // Hide Author
            update_field('field_56cadc4e0480b', false, $postId);
            // Hide Author image
            update_field('field_56cadc7b0480c', false, $postId);

            // @TODO Save Users Administration unit
            // if (class_exists('\\Intranet\\User\\AdministrationUnits')) {
            // }
        }
    }

    /**
     * Allow comments by default for this post type
     * @param  array $data    [description]
     * @param  array $postarr [description]
     * @return array          Modified data list
     */
    public function allowComments($data, $postarr) {
        if ($data['post_type'] == $this->postTypeSlug) {
            $data['comment_status'] = 'open';
        }

        return $data;
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

    public function excludedFields($exclude, $postType, $postId)
    {
        if ($postType === $this->postTypeSlug) {
            $exclude[] = 'file_upload';
            $exclude[] = 'sender-address';
            $exclude[] = 'input';
        }

        return $exclude;
    }

}
