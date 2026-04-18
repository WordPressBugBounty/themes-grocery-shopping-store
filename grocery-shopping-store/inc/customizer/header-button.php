<?php
/**
* Header Options.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

// Header Section.
$wp_customize->add_section( 'grocery_shopping_store_button_header_setting',
	array(
	'title'      => esc_html__( 'Header Settings', 'grocery-shopping-store' ),
	'priority'   => 10,
	'capability' => 'edit_theme_options',
	'panel'      => 'grocery_shopping_store_theme_option_panel',
	)
);

$wp_customize->add_setting('grocery_shopping_store_sticky',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_sticky'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_sticky',
    array(
        'label' => esc_html__('Enable Sticky Header', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_button_header_setting',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_header_search',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_header_search'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_header_search',
    array(
        'label' => esc_html__('Enable Search', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_button_header_setting',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_header_layout_button',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_header_layout_button'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_header_layout_button',
    array(
    'label'    => esc_html__( 'Header Button Text', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_button_header_setting',
    'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_header_layout_button_url',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_header_layout_button_url'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_header_layout_button_url',
    array(
    'label'    => esc_html__( 'Header Button Url', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_button_header_setting',
    'type'     => 'url',
    )
);

$wp_customize->add_setting('grocery_shopping_store_menu_font_size',
    array(
        'default'           => $grocery_shopping_store_default['grocery_shopping_store_menu_font_size'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_number_range',
    )
);
$wp_customize->add_control('grocery_shopping_store_menu_font_size',
    array(
        'label'       => esc_html__('Menu Font Size', 'grocery-shopping-store'),
        'section'     => 'grocery_shopping_store_button_header_setting',
        'type'        => 'number',
        'input_attrs' => array(
           'min'   => 1,
           'max'   => 30,
           'step'   => 1,
        ),
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_menu_text_transform',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_menu_text_transform'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_sanitize_menu_transform',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_menu_text_transform',
    array(
    'label'       => esc_html__( 'Menu Text Transform', 'grocery-shopping-store' ),
    'section'     => 'grocery_shopping_store_button_header_setting',
    'type'        => 'select',
    'choices'     => array(
        'capitalize' => esc_html__( 'Capitalize', 'grocery-shopping-store' ),
        'uppercase'  => esc_html__( 'Uppercase', 'grocery-shopping-store' ),
        'lowercase'    => esc_html__( 'Lowercase', 'grocery-shopping-store' ),
        ),
    )
);

$wp_customize->add_setting('grocery_shopping_store_header_menus_color', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'grocery_shopping_store_header_menus_color', array(
    'label'    => __('Main Menu Color', 'grocery-shopping-store'),
    'section'  => 'grocery_shopping_store_button_header_setting',
)));

$wp_customize->add_setting('grocery_shopping_store_header_menus_hover_color', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'grocery_shopping_store_header_menus_hover_color', array(
    'label'    => __('Main Menu Hover Color', 'grocery-shopping-store'),
    'section'  => 'grocery_shopping_store_button_header_setting',
)));

$wp_customize->add_setting('grocery_shopping_store_header_submenus_color', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'grocery_shopping_store_header_submenus_color', array(
    'label'    => __('Submenu Color', 'grocery-shopping-store'),
    'section'  => 'grocery_shopping_store_button_header_setting',
)));

$wp_customize->add_setting('grocery_shopping_store_header_submenus_hover_color', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'grocery_shopping_store_header_submenus_hover_color', array(
    'label'    => __('Submenu Hover Color', 'grocery-shopping-store'),
    'section'  => 'grocery_shopping_store_button_header_setting',
)));