<?php
/**
 * Grocery Shopping Store functions and definitions
 * @package Grocery Shopping Store
 */

if ( ! function_exists( 'grocery_shopping_store_after_theme_support' ) ) :

	function grocery_shopping_store_after_theme_support() {
		
		add_theme_support( 'automatic-feed-links' );

		add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        add_theme_support('woocommerce', array(
            'gallery_thumbnail_image_width' => 300,
        ));
		
        load_theme_textdomain( 'grocery-shopping-store', get_template_directory() . '/languages' );

		add_theme_support(
			'custom-background',
			array(
				'default-color' => 'ffffff',
			)
		);

		$GLOBALS['content_width'] = apply_filters( 'grocery_shopping_store_content_width', 1140 );
		
		add_theme_support( 'post-thumbnails' );

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 270,
				'width'       => 90,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);
		
		add_theme_support( 'title-tag' );

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			)
		);

		add_theme_support( 'post-formats', array(
			'video',  
			'audio',  
			'gallery',
			'quote',  
			'image',  
			'link',   
			'status', 
			'aside',  
			'chat',   
		) );
		
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'wp-block-styles' );

		require get_template_directory() . '/inc/metabox.php';
		require get_template_directory() . '/inc/homepage-setup/homepage-setup-settings.php';

		if (! defined( 'GROCERY_SHOPPING_STORE_DOCS_PRO' ) ){
		define('GROCERY_SHOPPING_STORE_DOCS_PRO',__('https://layout.omegathemes.com/steps/pro-grocery-shopping-store/','grocery-shopping-store'));
		}
		if (! defined( 'GROCERY_SHOPPING_STORE_BUY_NOW' ) ){
		define('GROCERY_SHOPPING_STORE_BUY_NOW',__('https://www.omegathemes.com/products/grocery-store-wordpress-theme','grocery-shopping-store'));
		}
		if (! defined( 'GROCERY_SHOPPING_STORE_SUPPORT_FREE' ) ){
		define('GROCERY_SHOPPING_STORE_SUPPORT_FREE',__('https://WordPress.org/support/theme/grocery-shopping-store/','grocery-shopping-store'));
		}
		if (! defined( 'GROCERY_SHOPPING_STORE_REVIEW_FREE' ) ){
		define('GROCERY_SHOPPING_STORE_REVIEW_FREE',__('https://WordPress.org/support/theme/grocery-shopping-store/reviews/#new-post','grocery-shopping-store'));
		}
		if (! defined( 'GROCERY_SHOPPING_STORE_DEMO_PRO' ) ){
		define('GROCERY_SHOPPING_STORE_DEMO_PRO',__('https://layout.omegathemes.com/grocery-shopping-store/','grocery-shopping-store'));
		}
		if (! defined( 'GROCERY_SHOPPING_STORE_LITE_DOCS_PRO' ) ){
		define('GROCERY_SHOPPING_STORE_LITE_DOCS_PRO',__('https://layout.omegathemes.com/steps/free-grocery-shopping-store/','grocery-shopping-store'));
		}
		if (! defined( 'GROCERY_SHOPPING_STORE_BUNDLE_BUTTON' ) ){
			define('GROCERY_SHOPPING_STORE_BUNDLE_BUTTON',__('https://www.omegathemes.com/products/wp-theme-bundle','grocery-shopping-store'));
		}
		if (! defined( 'GROCERY_SHOPPING_STORE_HIRE_EXPERT' ) ){
			define('GROCERY_SHOPPING_STORE_HIRE_EXPERT',__('https://www.omegathemes.com/pages/contact','grocery-shopping-store'));
		}

	}

endif;

add_action( 'after_setup_theme', 'grocery_shopping_store_after_theme_support' );

/**
 * Register and Enqueue Styles.
 */
function grocery_shopping_store_register_styles() {

	wp_enqueue_style( 'dashicons' );

    $grocery_shopping_store_theme_version = wp_get_theme()->get( 'Version' );
	$grocery_shopping_store_fonts_url = grocery_shopping_store_fonts_url();
    if( $grocery_shopping_store_fonts_url ){
    	require_once get_theme_file_path( 'lib/custom/css/wptt-webfont-loader.php' );
        wp_enqueue_style(
			'grocery-shopping-store-google-fonts',
			grocery_shopping_store_wptt_get_webfont_url( $grocery_shopping_store_fonts_url ),
			array(),
			$grocery_shopping_store_theme_version
		);
    }

    wp_enqueue_style( 'swiper', get_template_directory_uri() . '/lib/swiper/css/swiper-bundle.min.css');
    wp_enqueue_style( 'owl.carousel', get_template_directory_uri() . '/lib/custom/css/owl.carousel.min.css');
	wp_enqueue_style( 'grocery-shopping-store-style', get_stylesheet_uri(), array(), $grocery_shopping_store_theme_version );

	wp_enqueue_style( 'grocery-shopping-store-style', get_stylesheet_uri() );
	require get_parent_theme_file_path( '/custom_css.php' );
	wp_add_inline_style( 'grocery-shopping-store-style',$grocery_shopping_store_custom_css );

	$grocery_shopping_store_css = '';

	if ( get_header_image() ) :

		$grocery_shopping_store_css .=  '
			#center-header{
				background-image: url('.esc_url(get_header_image()).') !important;
				-webkit-background-size: cover !important;
				-moz-background-size: cover !important;
				-o-background-size: cover !important;
				background-size: cover !important;
			}';

	endif;

	wp_add_inline_style( 'grocery-shopping-store-style', $grocery_shopping_store_css );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}	

	wp_enqueue_script( 'imagesloaded' );
    wp_enqueue_script( 'masonry' );
	wp_enqueue_script( 'swiper', get_template_directory_uri() . '/lib/swiper/js/swiper-bundle.min.js', array('jquery'), '', 1);
	wp_enqueue_script( 'grocery-shopping-store-custom', get_template_directory_uri() . '/lib/custom/js/theme-custom-script.js', array('jquery'), '', 1);
	wp_enqueue_script( 'owl.carousel', get_template_directory_uri() . '/lib/custom/js/owl.carousel.js', array('jquery'), '', 1);

    // Global Query
    if( is_front_page() ){

    	$grocery_shopping_store_posts_per_page = absint( get_option('posts_per_page') );
        $grocery_shopping_store_c_paged = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
        $grocery_shopping_store_posts_args = array(
            'posts_per_page'        => $grocery_shopping_store_posts_per_page,
            'paged'                 => $grocery_shopping_store_c_paged,
        );
        $grocery_shopping_store_posts_qry = new WP_Query( $grocery_shopping_store_posts_args );
        $max = $grocery_shopping_store_posts_qry->max_num_pages;

    }else{
        global $wp_query;
        $max = $wp_query->max_num_pages;
        $grocery_shopping_store_c_paged = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;
    }

    $grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
    $grocery_shopping_store_pagination_layout = get_theme_mod( 'grocery_shopping_store_pagination_layout',$grocery_shopping_store_default['grocery_shopping_store_pagination_layout'] );
}

add_action( 'wp_enqueue_scripts', 'grocery_shopping_store_register_styles',200 );

function grocery_shopping_store_admin_enqueue_scripts_callback() {
    if ( ! did_action( 'wp_enqueue_media' ) ) {
    wp_enqueue_media();
    }
    wp_enqueue_script('grocery-shopping-store-uploaderjs', get_stylesheet_directory_uri() . '/lib/custom/js/uploader.js', array(), "1.0", true);
}
add_action( 'admin_enqueue_scripts', 'grocery_shopping_store_admin_enqueue_scripts_callback' );

/**
 * Register navigation menus uses wp_nav_menu in five places.
 */
function grocery_shopping_store_menus() {

	$grocery_shopping_store_locations = array(
		'grocery-shopping-store-primary-menu'  => esc_html__( 'Primary Menu', 'grocery-shopping-store' ),
	);

	register_nav_menus( $grocery_shopping_store_locations );
}

add_action( 'init', 'grocery_shopping_store_menus' );

add_filter('loop_shop_columns', 'grocery_shopping_store_loop_columns');
if (!function_exists('grocery_shopping_store_loop_columns')) {
	function grocery_shopping_store_loop_columns() {
		$grocery_shopping_store_columns = get_theme_mod( 'grocery_shopping_store_per_columns', 3 );
		return $grocery_shopping_store_columns;
	}
}

add_filter( 'loop_shop_per_page', 'grocery_shopping_store_per_page', 20 );
function grocery_shopping_store_per_page( $grocery_shopping_store_cols ) {
  	$grocery_shopping_store_cols = get_theme_mod( 'grocery_shopping_store_product_per_page', 9 );
	return $grocery_shopping_store_cols;
}

add_filter( 'woocommerce_output_related_products_args', 'grocery_shopping_store_products_args' );

function grocery_shopping_store_products_args( $grocery_shopping_store_args ) {

    $grocery_shopping_store_args['posts_per_page'] = get_theme_mod( 'grocery_shopping_store_custom_related_products_number', 6 );

    $grocery_shopping_store_args['columns'] = get_theme_mod( 'grocery_shopping_store_custom_related_products_number_per_row', 3 );

    return $grocery_shopping_store_args;
}

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/classes/class-svg-icons.php';
require get_template_directory() . '/classes/class-walker-menu.php';
require get_template_directory() . '/inc/customizer/customizer.php';
require get_template_directory() . '/inc/custom-functions.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/classes/body-classes.php';
require get_template_directory() . '/inc/widgets/widgets.php';
require get_template_directory() . '/inc/pagination.php';
require get_template_directory() . '/lib/breadcrumbs/breadcrumbs.php';
require get_template_directory() . '/lib/custom/css/dynamic-style.php';


function grocery_shopping_store_remove_customize_register() {
    global $wp_customize;

    $wp_customize->remove_setting( 'display_header_text' );
    $wp_customize->remove_control( 'display_header_text' );

}

add_action( 'customize_register', 'grocery_shopping_store_remove_customize_register', 11 );

// Apply styles based on customizer settings

function grocery_shopping_store_radio_sanitize(  $grocery_shopping_store_input, $grocery_shopping_store_setting  ) {
	$grocery_shopping_store_input = sanitize_key( $grocery_shopping_store_input );
	$grocery_shopping_store_choices = $grocery_shopping_store_setting->manager->get_control( $grocery_shopping_store_setting->id )->choices;
	return ( array_key_exists( $grocery_shopping_store_input, $grocery_shopping_store_choices ) ? $grocery_shopping_store_input : $grocery_shopping_store_setting->default );
}
require get_template_directory() . '/inc/general.php';


add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );

// NOTICE FUNCTION

/**
 * Whether the welcome notice should be shown on the current admin screen.
 */
function grocery_shopping_store_show_admin_notice() {
    if ( get_option( 'grocery_shopping_store_admin_notice' ) || is_network_admin() || ! current_user_can( 'manage_options' ) ) {
        return false;
    }

    $grocery_shopping_store_current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( $grocery_shopping_store_current_screen && 'appearance_page_groceryshoppingstore-wizard' === $grocery_shopping_store_current_screen->base ) {
        return false;
    }

    // Once every setup step is done the notice only stays on the Dashboard.
    if ( grocery_shopping_store_setup_is_complete() ) {
        return $grocery_shopping_store_current_screen && 'dashboard' === $grocery_shopping_store_current_screen->base;
    }

    return true;
}

/**
 * Whether every Getting Started step is done.
 */
function grocery_shopping_store_setup_is_complete() {
    return function_exists( 'grocery_shopping_store_get_setup_progress' ) && ! in_array( false, grocery_shopping_store_get_setup_progress(), true );
}

function grocery_shopping_store_admin_notice() {
    if ( ! grocery_shopping_store_show_admin_notice() ) {
        return;
    }

    $grocery_shopping_store_theme_args = wp_get_theme();
    $grocery_shopping_store_name = $grocery_shopping_store_theme_args->get( 'Name' );
    $grocery_shopping_store_progress = function_exists( 'grocery_shopping_store_get_setup_progress' ) ? grocery_shopping_store_get_setup_progress() : array();
    $grocery_shopping_store_done = count( array_filter( $grocery_shopping_store_progress ) );
    $grocery_shopping_store_total = max( 1, count( $grocery_shopping_store_progress ) );
    $grocery_shopping_store_complete = grocery_shopping_store_setup_is_complete();
    $grocery_shopping_store_dismiss_url = wp_nonce_url( add_query_arg( 'grocery_shopping_store_admin_notice', '1' ), 'grocery_shopping_store_dismiss_notice' );
    ?>
    <div class="notice omg-notice<?php echo $grocery_shopping_store_complete ? ' omg-notice--complete' : ''; ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'grocery_shopping_store_dismiss_notice' ) ); ?>">
        <div class="omg-notice__media">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>" alt="<?php echo esc_attr( $grocery_shopping_store_name ); ?>">
        </div>
        <div class="omg-notice__body">
            <p class="omg-notice__eyebrow">
                <?php
                if ( $grocery_shopping_store_complete ) {
                    /* translators: %s: theme name */
                    printf( esc_html__( '%s setup complete', 'grocery-shopping-store' ), esc_html( $grocery_shopping_store_name ) );
                } else {
                    /* translators: %s: theme name */
                    printf( esc_html__( 'Thanks for choosing %s', 'grocery-shopping-store' ), esc_html( $grocery_shopping_store_name ) );
                }
                ?>
            </p>
            <?php if ( $grocery_shopping_store_complete ) : ?>
                <h2 class="omg-notice__title"><?php esc_html_e( 'Your store is ready', 'grocery-shopping-store' ); ?></h2>
                <p class="omg-notice__text"><?php esc_html_e( 'Every setup step is done. Add your products, fine-tune the design in the Customizer, or unlock more layouts with Pro.', 'grocery-shopping-store' ); ?></p>
            <?php else : ?>
                <h2 class="omg-notice__title"><?php esc_html_e( 'Let’s set up your store in a few minutes', 'grocery-shopping-store' ); ?></h2>
                <p class="omg-notice__text"><?php esc_html_e( 'Install the recommended plugins, import the demo and make it yours, all from one Getting Started page.', 'grocery-shopping-store' ); ?></p>
            <?php endif; ?>
            <div class="omg-notice__progress">
                <span class="omg-progress"><span class="omg-progress__bar" style="width: <?php echo esc_attr( max( 3, round( $grocery_shopping_store_done / $grocery_shopping_store_total * 100 ) ) ); ?>%"></span></span>
                <span class="omg-notice__count">
                    <?php
                    /* translators: 1: steps done, 2: total steps */
                    printf( esc_html__( '%1$s of %2$s steps done', 'grocery-shopping-store' ), esc_html( $grocery_shopping_store_done ), esc_html( $grocery_shopping_store_total ) );
                    ?>
                </span>
            </div>
            <div class="omg-notice__actions">
                <?php if ( $grocery_shopping_store_complete ) : ?>
                    <a class="omg-btn omg-btn--primary" href="<?php echo esc_url( admin_url( 'themes.php?page=groceryshoppingstore-wizard' ) ); ?>"><?php esc_html_e( 'Getting Started', 'grocery-shopping-store' ); ?></a>
                    <a class="omg-btn omg-btn--outline" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank"><?php esc_html_e( 'View your site', 'grocery-shopping-store' ); ?></a>
                    <a class="omg-btn omg-btn--outline" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize', 'grocery-shopping-store' ); ?></a>
                <?php else : ?>
                    <a class="omg-btn omg-btn--primary" href="<?php echo esc_url( admin_url( 'themes.php?page=groceryshoppingstore-wizard&omg-focus=1' ) ); ?>"><?php echo $grocery_shopping_store_done ? esc_html__( 'Continue setup', 'grocery-shopping-store' ) : esc_html__( 'Get started', 'grocery-shopping-store' ); ?></a>
                <?php endif; ?>
                <a class="omg-btn omg-btn--premium" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUY_NOW ); ?>" target="_blank"><?php esc_html_e( 'Go To Premium', 'grocery-shopping-store' ); ?></a>
                <a class="omg-btn omg-btn--outline-pink" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUNDLE_BUTTON ); ?>" target="_blank"><?php esc_html_e( 'Get all WP themes for $69', 'grocery-shopping-store' ); ?></a>
            </div>
        </div>
        <a class="omg-notice__close" href="<?php echo esc_url( $grocery_shopping_store_dismiss_url ); ?>" aria-label="<?php esc_attr_e( 'Dismiss this notice', 'grocery-shopping-store' ); ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </a>
    </div>
    <?php
}
add_action( 'admin_notices', 'grocery_shopping_store_admin_notice' );

if ( ! function_exists( 'grocery_shopping_store_update_admin_notice' ) ) :
/**
 * Updating admin notice on dismiss (link fallback when JS is off)
 */
function grocery_shopping_store_update_admin_notice() {
    if ( isset( $_GET['grocery_shopping_store_admin_notice'] ) && '1' === $_GET['grocery_shopping_store_admin_notice'] && current_user_can( 'manage_options' ) ) {
        check_admin_referer( 'grocery_shopping_store_dismiss_notice' );
        update_option( 'grocery_shopping_store_admin_notice', true );
        wp_safe_redirect( remove_query_arg( array( 'grocery_shopping_store_admin_notice', '_wpnonce' ) ) );
        exit;
    }
}
endif;
add_action( 'admin_init', 'grocery_shopping_store_update_admin_notice' );

/**
 * Dismiss the welcome notice over AJAX
 */
function grocery_shopping_store_dismiss_admin_notice() {
    check_ajax_referer( 'grocery_shopping_store_dismiss_notice', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error();
    }
    update_option( 'grocery_shopping_store_admin_notice', true );
    wp_send_json_success();
}
add_action( 'wp_ajax_grocery_shopping_store_dismiss_notice', 'grocery_shopping_store_dismiss_admin_notice' );

// After Switch theme function
add_action( 'after_switch_theme', 'grocery_shopping_store_getstart_setup_options' );
function grocery_shopping_store_getstart_setup_options() {
    update_option( 'grocery_shopping_store_admin_notice', false );
}

