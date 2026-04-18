<?php
/**
* Additional Woocommerce Settings.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

// Additional Woocommerce Section.
$wp_customize->add_section( 'grocery_shopping_store_additional_woocommerce_options',
	array(
	'title'      => esc_html__( 'Additional Woocommerce Options', 'grocery-shopping-store' ),
	'priority'   => 210,
	'capability' => 'edit_theme_options',
	'panel'      => 'grocery_shopping_store_theme_option_panel',
	)
);

	$wp_customize->add_setting('grocery_shopping_store_per_columns',
		array(
		'default'           => $grocery_shopping_store_default['grocery_shopping_store_per_columns'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'grocery_shopping_store_sanitize_number_range',
		)
	);
	$wp_customize->add_control('grocery_shopping_store_per_columns',
		array(
		'label'       => esc_html__('Products Per Column', 'grocery-shopping-store'),
		'section'     => 'grocery_shopping_store_additional_woocommerce_options',
		'type'        => 'number',
		'input_attrs' => array(
		'min'   => 1,
		'max'   => 6,
		'step'   => 1,
		),
		)
	);

	$wp_customize->add_setting('grocery_shopping_store_product_per_page',
		array(
		'default'           => $grocery_shopping_store_default['grocery_shopping_store_product_per_page'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'grocery_shopping_store_sanitize_number_range',
		)
	);
	$wp_customize->add_control('grocery_shopping_store_product_per_page',
		array(
		'label'       => esc_html__('Products Per Page', 'grocery-shopping-store'),
		'section'     => 'grocery_shopping_store_additional_woocommerce_options',
		'type'        => 'number',
		'input_attrs' => array(
		'min'   => 1,
		'max'   => 100,
		'step'   => 1,
		),
		)
	);

	$wp_customize->add_setting('grocery_shopping_store_show_hide_related_product',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_show_hide_related_product'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
	);
	$wp_customize->add_control('grocery_shopping_store_show_hide_related_product',
	    array(
	        'label' => esc_html__('Enable Related Products', 'grocery-shopping-store'),
	        'section' => 'grocery_shopping_store_additional_woocommerce_options',
	        'type' => 'checkbox',
	    )
	);

	$wp_customize->add_setting('grocery_shopping_store_custom_related_products_number',
		array(
		'default'           => $grocery_shopping_store_default['grocery_shopping_store_custom_related_products_number'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'grocery_shopping_store_sanitize_number_range',
		)
	);
	$wp_customize->add_control('grocery_shopping_store_custom_related_products_number',
		array(
		'label'       => esc_html__('Related Products Per Page', 'grocery-shopping-store'),
		'section'     => 'grocery_shopping_store_additional_woocommerce_options',
		'type'        => 'number',
		'input_attrs' => array(
		'min'   => 1,
		'max'   => 10,
		'step'   => 1,
		),
		)
	);

	$wp_customize->add_setting('grocery_shopping_store_custom_related_products_number_per_row',
		array(
		'default'           => $grocery_shopping_store_default['grocery_shopping_store_custom_related_products_number_per_row'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'grocery_shopping_store_sanitize_number_range',
		)
	);
	$wp_customize->add_control('grocery_shopping_store_custom_related_products_number_per_row',
		array(
		'label'       => esc_html__('Related Products Per Row', 'grocery-shopping-store'),
		'section'     => 'grocery_shopping_store_additional_woocommerce_options',
		'type'        => 'number',
		'input_attrs' => array(
		'min'   => 1,
		'max'   => 5,
		'step'   => 1,
		),
		)
	);