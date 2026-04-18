<?php
/**
* Global Color  Settings.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

// Layout Section.
$wp_customize->add_section( 'grocery_shopping_store_global_color_setting',
	array(
	'title'      => esc_html__( 'Global Color Settings', 'grocery-shopping-store' ),
	'priority'   => 1,
	'capability' => 'edit_theme_options',
	'panel'      => 'grocery_shopping_store_theme_option_panel',
	)
);

$wp_customize->add_setting( 'grocery_shopping_store_global_color',
    array(
    'default'           => '#FF851C',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control( 
    new WP_Customize_Color_Control( 
    $wp_customize, 
    'grocery_shopping_store_global_color',
    array(
        'label'      => esc_html__( 'Global Color', 'grocery-shopping-store' ),
        'section'    => 'grocery_shopping_store_global_color_setting',
        'settings'   => 'grocery_shopping_store_global_color',
    ) ) 
);