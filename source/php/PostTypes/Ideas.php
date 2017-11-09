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
                'menu_icon'     => 'dashicons-lightbulb',
                'hierarchical'  => true,
                'supports'      => array('title', 'revisions', 'editor', 'thumbnail'),
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
