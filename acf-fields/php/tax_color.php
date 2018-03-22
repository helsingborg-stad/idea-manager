<?php 



    'key' => 'group_5ab3a45759ba5',
    'title' => __('Taxonomy color', 'idea-manager'),
    'fields' => array(
        0 => array(
            'key' => 'field_5ab3a4798ad9a',
            'label' => __('Background color', 'idea-manager'),
            'name' => 'taxonomy_color',
            'type' => 'color_picker',
            'instructions' => __('Select a custom background color for the taxonomy.', 'idea-manager'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'taxonomy',
                'operator' => '==',
                'value' => 'idea_statuses',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));
