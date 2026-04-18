<?php
/**
* Noting Found Page Settings.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

$wp_customize->add_section( 'grocery_shopping_store_noting_found_page_settings',
    array(
        'title'      => esc_html__( 'Nothing Found Page Settings', 'grocery-shopping-store' ),
        'priority'   => 200,
        'capability' => 'edit_theme_options',
        'panel'      => 'grocery_shopping_store_theme_addons_panel',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_noting_found_main_title',
    array(
        'default'           => 'Nothing Found',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_noting_found_main_title',
    array(
        'label'    => esc_html__( 'Main Title', 'grocery-shopping-store' ),
        'section'  => 'grocery_shopping_store_noting_found_page_settings',
        'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_noting_found_para',
    array(
        'default'           => 'Sorry, but nothing matched your search terms. Please try again with some different keywords.',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_noting_found_para',
    array(
        'label'    => esc_html__( 'Para Text', 'grocery-shopping-store' ),
        'section'  => 'grocery_shopping_store_noting_found_page_settings',
        'type'     => 'text',
    )
);

$wp_customize->add_setting('grocery_shopping_store_noting_found_saerch',
    array(
        'default' => 1,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_noting_found_saerch',
    array(
        'label' => esc_html__('Enable/Disable Search', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_noting_found_page_settings',
        'type' => 'checkbox',
    )
);