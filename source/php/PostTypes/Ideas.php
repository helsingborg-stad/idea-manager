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
                'rewrite'              =>   array(
                    'slug'       =>   'idea',
                    'with_front' =>   false
                ),
                'has_archive'   => true,
                'hierarchical'  => false,
                'supports'      => array('title', 'revisions', 'thumbnail', 'author'),
            )
        );

        // Taxonomies
        $this->addTaxonomy(
            __('Status', 'idea-manager'),
            __('Statuses', 'idea-manager'),
            'idea_statuses',
            array(
                'hierarchical'  => true,
            )
        );
    }
}
