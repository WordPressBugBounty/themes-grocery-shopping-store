<?php
/**
* Posts Settings.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

// Single Post Section.
$wp_customize->add_section( 'grocery_shopping_store_single_posts_settings',
    array(
    'title'      => esc_html__( 'Single Meta Information Settings', 'grocery-shopping-store' ),
    'priority'   => 35,
    'capability' => 'edit_theme_options',
    'panel'      => 'grocery_shopping_store_theme_option_panel',
    )
);

$wp_customize->add_setting('grocery_shopping_store_display_single_post_image',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_display_single_post_image'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_display_single_post_image',
    array(
        'label' => esc_html__('Enable Single Posts Image', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_single_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_post_author',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_post_author'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_post_author',
    array(
        'label' => esc_html__('Enable Posts Author', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_single_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_post_date',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_post_date'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_post_date',
    array(
        'label' => esc_html__('Enable Posts Date', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_single_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_post_category',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_post_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_post_category',
    array(
        'label' => esc_html__('Enable Posts Category', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_single_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_post_tags',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_post_tags'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_post_tags',
    array(
        'label' => esc_html__('Enable Posts Tags', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_single_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_single_page_content_alignment',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_single_page_content_alignment'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_sanitize_page_content_alignment',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_single_page_content_alignment',
    array(
    'label'       => esc_html__( 'Single Page Content Alignment', 'grocery-shopping-store' ),
    'section'     => 'grocery_shopping_store_single_posts_settings',
    'type'        => 'select',
    'choices'     => array(
        'left' => esc_html__( 'Left', 'grocery-shopping-store' ),
        'center'  => esc_html__( 'Center', 'grocery-shopping-store' ),
        'right'    => esc_html__( 'Right', 'grocery-shopping-store' ),
        ),
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_single_post_content_alignment',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_single_post_content_alignment'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_sanitize_page_content_alignment',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_single_post_content_alignment',
    array(
    'label'       => esc_html__( 'Single Post Content Alignment', 'grocery-shopping-store' ),
    'section'     => 'grocery_shopping_store_single_posts_settings',
    'type'        => 'select',
    'choices'     => array(
        'left' => esc_html__( 'Left', 'grocery-shopping-store' ),
        'center'  => esc_html__( 'Center', 'grocery-shopping-store' ),
        'right'    => esc_html__( 'Right', 'grocery-shopping-store' ),
        ),
    )
);

// Archive Post Section.
$wp_customize->add_section( 'grocery_shopping_store_posts_settings',
    array(
    'title'      => esc_html__( 'Archive Meta Information Settings', 'grocery-shopping-store' ),
    'priority'   => 36,
    'capability' => 'edit_theme_options',
    'panel'      => 'grocery_shopping_store_theme_option_panel',
    )
);

$wp_customize->add_setting('grocery_shopping_store_display_archive_post_format_icon',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_display_archive_post_format_icon'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_display_archive_post_format_icon',
    array(
        'label' => esc_html__('Enable Posts Format Icon', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_display_archive_post_image',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_display_archive_post_image'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_display_archive_post_image',
    array(
        'label' => esc_html__('Enable Posts Image', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_display_archive_post_category',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_display_archive_post_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_display_archive_post_category',
    array(
        'label' => esc_html__('Enable Posts Category', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_display_archive_post_title',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_display_archive_post_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_display_archive_post_title',
    array(
        'label' => esc_html__('Enable Posts Title', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_display_archive_post_content',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_display_archive_post_content'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_display_archive_post_content',
    array(
        'label' => esc_html__('Enable Posts Content', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_display_archive_post_button',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_display_archive_post_button'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_display_archive_post_button',
    array(
        'label' => esc_html__('Enable Posts Button', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_excerpt_limit',
    array(
        'default'           => $grocery_shopping_store_default['grocery_shopping_store_excerpt_limit'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_number_range',
    )
);
$wp_customize->add_control('grocery_shopping_store_excerpt_limit',
    array(
        'label'       => esc_html__('Blog Posts Excerpt limit', 'grocery-shopping-store'),
        'section'     => 'grocery_shopping_store_posts_settings',
        'type'        => 'number',
        'input_attrs' => array(
           'min'   => 1,
           'max'   => 100,
           'step'   => 1,
        ),
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_archive_image_size',
	array(
	'default'           => 'medium',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'grocery_shopping_store_sanitize_select',
	)
);
$wp_customize->add_control( 'grocery_shopping_store_archive_image_size',
	array(
	'label'       => esc_html__( 'Blog Posts Image Size', 'grocery-shopping-store' ),
	'section'     => 'grocery_shopping_store_posts_settings',
	'type'        => 'select',
	'choices'               => array(
		'full' => esc_html__( 'Large Size Image', 'grocery-shopping-store' ),
		'large' => esc_html__( 'Big Size Image', 'grocery-shopping-store' ),
		'medium' => esc_html__( 'Medium Size Image', 'grocery-shopping-store' ),
		'small' => esc_html__( 'Small Size Image', 'grocery-shopping-store' ),
		'xsmall' => esc_html__( 'Extra Small Size Image', 'grocery-shopping-store' ),
		'thumbnail' => esc_html__( 'Thumbnail Size Image', 'grocery-shopping-store' ),
	    ),
	)
);

$wp_customize->add_setting('grocery_shopping_store_posts_per_columns',
    array(
    'default'           => '3',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_sanitize_number_range',
    )
);
$wp_customize->add_control('grocery_shopping_store_posts_per_columns',
    array(
    'label'       => esc_html__('Blog Posts Per Column', 'grocery-shopping-store'),
    'section'     => 'grocery_shopping_store_posts_settings',
    'type'        => 'number',
    'input_attrs' => array(
    'min'   => 1,
    'max'   => 5,
    'step'   => 1,
    ),
    )
);