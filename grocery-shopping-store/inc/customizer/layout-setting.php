<?php
/**
* Layouts Settings.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

// Layout Section.
$wp_customize->add_section( 'grocery_shopping_store_layout_setting',
	array(
	'title'      => esc_html__( 'Sidebar Settings', 'grocery-shopping-store' ),
	'priority'   => 20,
	'capability' => 'edit_theme_options',
	'panel'      => 'grocery_shopping_store_theme_option_panel',
	)
);

$wp_customize->add_setting( 'grocery_shopping_store_global_sidebar_layout',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_global_sidebar_layout'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_sanitize_sidebar_option',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_global_sidebar_layout',
    array(
    'label'       => esc_html__( 'Global Sidebar Layout', 'grocery-shopping-store' ),
    'section'     => 'grocery_shopping_store_layout_setting',
    'type'        => 'select',
    'choices'     => array(
        'right-sidebar' => esc_html__( 'Right Sidebar', 'grocery-shopping-store' ),
        'left-sidebar'  => esc_html__( 'Left Sidebar', 'grocery-shopping-store' ),
        'no-sidebar'    => esc_html__( 'No Sidebar', 'grocery-shopping-store' ),
        ),
    )
);

$wp_customize->add_setting('grocery_shopping_store_page_sidebar_layout', array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_global_sidebar_layout'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_sanitize_sidebar_option',
));

$wp_customize->add_control('grocery_shopping_store_page_sidebar_layout', array(
    'label'       => esc_html__('Single Page Sidebar Layout', 'grocery-shopping-store'),
    'section'     => 'grocery_shopping_store_layout_setting',
    'type'        => 'select',
    'choices'     => array(
        'right-sidebar' => esc_html__('Right Sidebar', 'grocery-shopping-store'),
        'left-sidebar'  => esc_html__('Left Sidebar', 'grocery-shopping-store'),
        'no-sidebar'    => esc_html__('No Sidebar', 'grocery-shopping-store'),
    ),
));

$wp_customize->add_setting('grocery_shopping_store_post_sidebar_layout', array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_global_sidebar_layout'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_sanitize_sidebar_option',
));

$wp_customize->add_control('grocery_shopping_store_post_sidebar_layout', array(
    'label'       => esc_html__('Single Post Sidebar Layout', 'grocery-shopping-store'),
    'section'     => 'grocery_shopping_store_layout_setting',
    'type'        => 'select',
    'choices'     => array(
        'right-sidebar' => esc_html__('Right Sidebar', 'grocery-shopping-store'),
        'left-sidebar'  => esc_html__('Left Sidebar', 'grocery-shopping-store'),
        'no-sidebar'    => esc_html__('No Sidebar', 'grocery-shopping-store'),
    ),
));

$wp_customize->add_setting('grocery_shopping_store_sticky_sidebar',
    array(
        'default'           => true,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_sticky_sidebar',
    array(
        'label' => esc_html__('Enable/Disable Sticky Sidebar', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_layout_setting',
        'type' => 'checkbox',
    )
);