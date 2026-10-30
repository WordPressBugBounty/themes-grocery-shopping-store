<?php
/**
 * Settings for demo import
 *
 */

/**
 * Define constants
 **/
if ( ! defined( 'WHIZZIE_DIR' ) ) {
	define( 'WHIZZIE_DIR', dirname( __FILE__ ) );
}
require trailingslashit( WHIZZIE_DIR ) . 'homepage-setup-contents.php';
$grocery_shopping_store_current_theme = wp_get_theme();
$grocery_shopping_store_theme_title = $grocery_shopping_store_current_theme->get( 'Name' );


/**
 * Make changes below
 **/

// Change the title and slug of your wizard page
$grocery_shopping_store_config['grocery_shopping_store_page_slug'] 	= 'grocery-shopping-store';
$grocery_shopping_store_config['grocery_shopping_store_page_title']	= __( 'Getting Started', 'grocery-shopping-store' );

// Only 'title' and 'desc' can be changed here
$grocery_shopping_store_config['steps'] = array(
	'plugins' => array(
		'id'	=> 'plugins',
		'title'	=> __( 'Install essential plugins', 'grocery-shopping-store' ),
		'desc'	=> __( 'Recommended for demo layouts and shop pages', 'grocery-shopping-store' ),
	),
	'demo' => array(
		'id'	=> 'demo',
		'title'	=> __( 'Import demo content', 'grocery-shopping-store' ),
		'desc'	=> __( 'Pages, products, menus and widgets', 'grocery-shopping-store' ),
	),
	'customize' => array(
		'id'	=> 'customize',
		'title'	=> __( 'Make it yours', 'grocery-shopping-store' ),
		'desc'	=> __( 'Logo, colors, header and homepage', 'grocery-shopping-store' ),
	),
	'launch' => array(
		'id'	=> 'launch',
		'title'	=> __( 'Launch your store', 'grocery-shopping-store' ),
		'desc'	=> __( 'Preview and go live', 'grocery-shopping-store' ),
	)
);

/**
 * This kicks off the wizard
 **/
if( class_exists( 'Grocery_Shopping_Store_Whizzie' ) ) {
	$Grocery_Shopping_Store_Whizzie = new Grocery_Shopping_Store_Whizzie( $grocery_shopping_store_config );
}