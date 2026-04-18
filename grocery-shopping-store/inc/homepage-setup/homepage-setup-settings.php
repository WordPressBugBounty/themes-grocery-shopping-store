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
$config['grocery_shopping_store_page_slug'] 	= 'grocery-shopping-store';
$config['grocery_shopping_store_page_title']	= 'Homepage Setup';

$config['steps'] = array(
	'plugins' => array(
		'id'			=> 'plugins',
		'title'			=> __( 'Install and Activate Essential Plugins', 'grocery-shopping-store' ),
		'icon'			=> 'admin-plugins',
		'button_text'	=> __( 'Install Plugins', 'grocery-shopping-store' ),
		'can_skip'		=> true
	),
	'widgets' => array(
		'id'			=> 'widgets',
		'title'			=> __( 'Setup Home Page', 'grocery-shopping-store' ),
		'icon'			=> 'welcome-widgets-menus',
		'button_text'	=> __( 'Start Home Page Setup', 'grocery-shopping-store' ),
		'can_skip'		=> true
	),
	'done' => array(
		'id'			=> 'done',
		'title'			=> __( 'Customize Your Site', 'grocery-shopping-store' ),
		'icon'			=> 'yes',
	)
);

/**
 * This kicks off the wizard
 **/
if( class_exists( 'Grocery_Shopping_Store_Whizzie' ) ) {
	$Grocery_Shopping_Store_Whizzie = new Grocery_Shopping_Store_Whizzie( $config );
}