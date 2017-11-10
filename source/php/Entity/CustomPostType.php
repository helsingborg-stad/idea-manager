<?php

namespace IdeaManager\Entity;

class CustomPostType
{
    public $name_singular;
    public $name_plural;
    public $slug;
    public $post_type_args;
    public $post_type_labels;

    /**
     * Registers a custom post type
     * @param string $name   Post type name
     * @param array  $args   Post type arguments
     * @param array  $labels Post type labels
     */
    public function __construct($name, $plural, $slug, $args = array(), $labels = array())
    {
        $this->name_singular = $name;
        $this->name_plural = $plural;
        $this->slug = $slug;
        $this->post_type_args   = $args;
        $this->post_type_labels = $labels;

        if (!post_type_exists($slug)) {
        	add_action('init', array(&$this, 'registerPostType'));
        }
    }

    /**
     * Registers the post type with WP
     * @return void
     */
    public function registerPostType()
    {
        // We set the default labels based on the post type name and plural. We overwrite them with the given labels.
        $labels = array_merge(
            // Default
            array(
            'name'                => $this->name_singular,
            'singular_name'       => $this->name_singular,
            'add_new'             => sprintf(__('Add new %s', 'idea-manager'), $this->name_singular),
            'add_new_item'        => sprintf(__('Add new %s', 'idea-manager'), $this->name_singular),
            'edit_item'           => sprintf(__('Edit %s', 'idea-manager'), $this->name_singular),
            'new_item'            => sprintf(__('New %s', 'idea-manager'), $this->name_singular),
            'view_item'           => sprintf(__('View %s', 'idea-manager'), $this->name_singular),
            'search_items'        => sprintf(__('Search %s', 'idea-manager'), $this->name_plural),
            'not_found'           => sprintf(__('No %s found', 'idea-manager'), $this->name_plural),
            'not_found_in_trash'  => sprintf(__('No %s found in trash', 'idea-manager'), $this->name_plural),
            'parent_item_colon'   => sprintf(__('Parent %s:', 'idea-manager'), $this->name_singular),
            'menu_name'           => $this->name_plural,
            ),

            // Given labels
            $this->post_type_labels
        );

        // Same principle as the labels. We set some default and overwite them with the given arguments.
        $args = array_merge(
            // Default
            array(
                'label'                => $this->name_plural,
                'labels'               => $labels,
                'public'               => true,
                'show_ui'              => true,
                'supports'             => array('title', 'editor'),
                'show_in_nav_menus'    => true,
            ),

            // Given args
            $this->post_type_args
        );

        // Register the post type
        register_post_type($this->slug, $args);
    }

    /**
     * Registers a taxonomy to the post type
     * @param string $name_singular Singular name
     * @param string $name_plural   Plural name
     * @param array  $args          Taxonomy arguments
     * @param array  $labels        Taxonomy labels
     */
    public function addTaxonomy($name_singular, $name_plural, $slug, $args = array(), $labels = array())
    {
        if (!empty($name_singular)) {
            // We need to know the post type name, so the new taxonomy can be attached to it.
            $post_type = $this->slug;

            // Taxonomy properties
            $taxonomy_labels = $labels;
            $taxonomy_args   = $args;

            if (!taxonomy_exists($slug)) {
                // Default labels, overwrite them with the given labels.
                $labels = array_merge(

                    // Default
                    array(
                        'name'                 => $name_singular,
                        'singular_name'        => $name_singular,
                        'search_items'         => sprintf(__('Search %s', 'idea-manager'), $name_plural),
                        'all_items'            => sprintf(__('All %s', 'idea-manager'), $name_plural),
                        'parent_item'          => sprintf(__('Parent %s', 'idea-manager'), $name_singular),
                        'parent_item_colon'    => sprintf(__('Parent %s:', 'idea-manager'), $name_singular),
                        'edit_item'            => sprintf(__('Edit %s', 'idea-manager'), $name_singular),
                        'update_item'          => sprintf(__('Update %s', 'idea-manager'), $name_singular),
                        'add_new_item'         => sprintf(__('Add new %s', 'idea-manager'), $name_singular),
                        'new_item_name'        => sprintf(__('New %s name', 'idea-manager'), $name_singular),
                        'menu_name'            => $name_plural,
                    ),

                    // Given labels
                    $taxonomy_labels
                );

                // Default arguments, overwitten with the given arguments
                $args = array_merge(
                    // Default
                    array(
                        'label'				=> $name_plural,
                        'labels'            => $labels,
                        'public'            => true,
                        'show_ui'           => true,
                        'show_in_nav_menus' => true,
                        '_builtin'          => false,
                    ),

                    // Given
                    $taxonomy_args
                );

                // Add the taxonomy to the post type
                add_action('init',
                    function () use ($slug, $post_type, $args) {
                        register_taxonomy($slug, $post_type, $args);
                    }
                );
            } else {
                add_action('init',
                            function () use ($slug, $post_type) {
                                register_taxonomy_for_object_type($slug, $post_type);
                            }
                        );
            }
        }
    }
}
