<?php
/**
* 404 Page Settings.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

$wp_customize->add_section( 'grocery_shopping_store_404_page_settings',
    array(
        'title'      => esc_html__( '404 Page Settings', 'grocery-shopping-store' ),
        'priority'   => 200,
        'capability' => 'edit_theme_options',
        'panel'      => 'grocery_shopping_store_theme_addons_panel',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_404_main_title',
    array(
        'default'           => $grocery_shopping_store_default['grocery_shopping_store_404_main_title'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_404_main_title',
    array(
        'label'    => esc_html__( '404 Main Title', 'grocery-shopping-store' ),
        'section'  => 'grocery_shopping_store_404_page_settings',
        'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_404_subtitle_one',
    array(
        'default'           => $grocery_shopping_store_default['grocery_shopping_store_404_subtitle_one'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_404_subtitle_one',
    array(
        'label'    => esc_html__( '404 Sub Title One', 'grocery-shopping-store' ),
        'section'  => 'grocery_shopping_store_404_page_settings',
        'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_404_para_one',
    array(
        'default'           => $grocery_shopping_store_default['grocery_shopping_store_404_para_one'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_404_para_one',
    array(
        'label'    => esc_html__( '404 Para Text One', 'grocery-shopping-store' ),
        'section'  => 'grocery_shopping_store_404_page_settings',
        'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_404_subtitle_two',
    array(
        'default'           => $grocery_shopping_store_default['grocery_shopping_store_404_subtitle_two'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_404_subtitle_two',
    array(
        'label'    => esc_html__( '404 Sub Title Two', 'grocery-shopping-store' ),
        'section'  => 'grocery_shopping_store_404_page_settings',
        'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_404_para_two',
    array(
        'default'           => $grocery_shopping_store_default['grocery_shopping_store_404_para_two'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_404_para_two',
    array(
        'label'    => esc_html__( '404 Para Text Two', 'grocery-shopping-store' ),
        'section'  => 'grocery_shopping_store_404_page_settings',
        'type'     => 'text',
    )
);