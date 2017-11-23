<?php

namespace IdeaManager\PostTypes;

class Ideas extends \IdeaManager\Entity\CustomPostType
{
    public function __construct()
    {
        parent::__construct(
            __('Idea', 'idea-manager'),
            __('Ideas', 'idea-manager'),
            'idea',
            array(
                'public'        => true,
                'show_ui'       => true,
                'menu_icon'     => 'dashicons-lightbulb',
                'has_archive'   => true,
                'hierarchical'  => false,
                'supports'      => array('title', 'revisions', 'thumbnail', 'author'),
                'taxonomies'    => array(),
            )
        );

        // Status taxonomy
        $this->addTaxonomy(
            __('Status', 'idea-manager'),
            __('Statuses', 'idea-manager'),
            'idea_statuses',
            array(
                'hierarchical'  => false,
            )
        );

        // Administration unit taxonomy
        $this->addTaxonomy(
            __('Administration unit', 'idea-manager'),
            __('Administration units', 'idea-manager'),
            'idea_administration_units',
            array(
                'hierarchical'  => false,
            )
        );

        // Set default post type data
        add_action('save_post_' . $this->slug, array($this, 'setDefaultContent'));
    }

    public function setDefaultContent($postId)
    {
        $currentUser = wp_get_current_user();
        $post = get_post($postId);

        if ($post->post_date == $post->post_modified) {
            // Set default status
            wp_set_object_terms($postId, 'Ej läst', 'idea_statuses');

            // Set Administration unit
            // if (class_exists('\\Intranet\\User\\AdministrationUnits')) {
            // }
        }
    }

}
