<?php
/**
* Typography Settings.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

// Layout Section.
$wp_customize->add_section( 'grocery_shopping_store_typography_setting',
	array(
	'title'      => esc_html__( 'Typography Settings', 'grocery-shopping-store' ),
	'priority'   => 21,
	'capability' => 'edit_theme_options',
	'panel'      => 'grocery_shopping_store_theme_option_panel',
	)
);

// -----------------  Font array
$grocery_shopping_store_fonts = array(
    'Select'           => __('Default Font', 'grocery-shopping-store'),
    'bad-script' => 'Bad Script',
    'bitter'     => 'Bitter',
    'cuprum'     => 'Cuprum',
    'exo-2'      => 'Exo 2',
    'jost'       => 'Jost',
    'oswald'     => 'Oswald',
    'roboto'     => 'Roboto',
    'NotoSans'     => 'NotoSans',
);

 // -----------------  General text font
 $wp_customize->add_setting( 'grocery_shopping_store_content_typography_font', array(
    'default'           => 'NotoSans',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_radio_sanitize',
) );
$wp_customize->add_control( 'grocery_shopping_store_content_typography_font', array(
    'type'     => 'select',
    'label'    => esc_html__( 'General Content Font', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_typography_setting',
    'settings' => 'grocery_shopping_store_content_typography_font',
    'choices'  => $grocery_shopping_store_fonts,
) );

 // -----------------  General Heading Font
$wp_customize->add_setting( 'grocery_shopping_store_heading_typography_font', array(
    'default'           => 'NotoSans',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_radio_sanitize',
) );
$wp_customize->add_control( 'grocery_shopping_store_heading_typography_font', array(
    'type'     => 'select',
    'label'    => esc_html__( 'General Heading Font', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_typography_setting',
    'settings' => 'grocery_shopping_store_heading_typography_font',
    'choices'  => $grocery_shopping_store_fonts,
) );