<?php
/**
 * Getting Started page and setup wizard
 *
 * @package Grocery_Shopping_Store_Whizzie
 * @author Omega Themes
 * @since 1.0.0
 */

/**
 * Setup step ids in the order they are shown.
 */
function grocery_shopping_store_setup_step_ids() {
	return array( 'plugins', 'demo', 'customize', 'launch' );
}

/**
 * Check whether every plugin registered with TGMPA is active and up to date.
 */
function grocery_shopping_store_setup_plugins_ready() {
	if ( ! isset( $GLOBALS['tgmpa'] ) ) {
		return true;
	}
	$grocery_shopping_store_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
	foreach ( $grocery_shopping_store_instance->plugins as $grocery_shopping_store_slug => $grocery_shopping_store_plugin ) {
		if ( ! $grocery_shopping_store_instance->is_plugin_active( $grocery_shopping_store_slug ) || false !== $grocery_shopping_store_instance->does_plugin_have_update( $grocery_shopping_store_slug ) ) {
			return false;
		}
	}
	return true;
}

/**
 * Get the completion state of each setup step.
 *
 * @return array step id => bool
 */
function grocery_shopping_store_get_setup_progress() {
	$grocery_shopping_store_saved = (array) get_option( 'grocery_shopping_store_setup_progress', array() );

	// Demo imported before progress tracking existed. Every Omega theme uses frontpage.php, so look for
	// the primary menu only this theme's import creates; another theme's demo must not count.
	$grocery_shopping_store_demo_found = (bool) wp_get_nav_menu_object( 'Grocery Shopping Store Primary Menu' );

	return array(
		'plugins'   => ! empty( $grocery_shopping_store_saved['plugins'] ) || grocery_shopping_store_setup_plugins_ready(),
		'demo'      => ! empty( $grocery_shopping_store_saved['demo'] ) || $grocery_shopping_store_demo_found,
		'customize' => ! empty( $grocery_shopping_store_saved['customize'] ),
		'launch'    => ! empty( $grocery_shopping_store_saved['launch'] ),
	);
}

/**
 * Save a step as done.
 *
 * @param string $grocery_shopping_store_step Step id.
 */
function grocery_shopping_store_set_setup_step_done( $grocery_shopping_store_step ) {
	if ( ! in_array( $grocery_shopping_store_step, grocery_shopping_store_setup_step_ids(), true ) ) {
		return;
	}
	$grocery_shopping_store_saved = (array) get_option( 'grocery_shopping_store_setup_progress', array() );
	$grocery_shopping_store_saved[ $grocery_shopping_store_step ] = true;
	update_option( 'grocery_shopping_store_setup_progress', $grocery_shopping_store_saved );
}

class Grocery_Shopping_Store_Whizzie {

	protected $version = '1.2.0';

	/** @var string Current theme name, used as namespace in actions. */
	protected $grocery_shopping_store_theme_name = '';
	protected $grocery_shopping_store_theme_title = '';

	/** @var string Wizard page slug and title. */
	protected $grocery_shopping_store_page_slug = '';
	protected $grocery_shopping_store_page_title = '';

	/** @var string Hook suffix returned by add_theme_page(). */
	protected $page_hook = '';

	/** @var array Wizard steps set by user. */
	protected $config_steps = array();

	/**
	 * Relative plugin url for this plugin folder
	 * @since 1.0.0
	 * @var string
	 */
	protected $grocery_shopping_store_plugin_url = '';

	public $grocery_shopping_store_plugin_path;
	public $parent_slug;

	/**
	 * TGMPA instance storage
	 *
	 * @var object
	 */
	protected $tgmpa_instance;

	/**
	 * TGMPA Menu slug
	 *
	 * @var string
	 */
	protected $tgmpa_menu_slug = 'tgmpa-install-plugins';

	/**
	 * TGMPA Menu url
	 *
	 * @var string
	 */
	protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';

	/**
	 * Constructor
	 *
	 * @param $grocery_shopping_store_config	Our config parameters
	 */
	public function __construct( $grocery_shopping_store_config ) {
		$this->set_vars( $grocery_shopping_store_config );
		$this->init();
	}

	/**
	 * Set some settings
	 * @since 1.0.0
	 * @param $grocery_shopping_store_config	Our config parameters
	 */
	public function set_vars( $grocery_shopping_store_config ) {

		require_once trailingslashit( WHIZZIE_DIR ) . 'tgm/class-tgm-plugin-activation.php';
		require_once trailingslashit( WHIZZIE_DIR ) . 'tgm/tgm.php';

		if( isset( $grocery_shopping_store_config['grocery_shopping_store_page_slug'] ) ) {
			$this->grocery_shopping_store_page_slug = esc_attr( $grocery_shopping_store_config['grocery_shopping_store_page_slug'] );
		}
		if( isset( $grocery_shopping_store_config['grocery_shopping_store_page_title'] ) ) {
			$this->grocery_shopping_store_page_title = esc_attr( $grocery_shopping_store_config['grocery_shopping_store_page_title'] );
		}
		if( isset( $grocery_shopping_store_config['steps'] ) ) {
			$this->config_steps = $grocery_shopping_store_config['steps'];
		}

		$this->grocery_shopping_store_plugin_path = trailingslashit( dirname( __FILE__ ) );
		$grocery_shopping_store_relative_url = str_replace( get_template_directory(), '', $this->grocery_shopping_store_plugin_path );
		$this->grocery_shopping_store_plugin_url = trailingslashit( get_template_directory_uri() . $grocery_shopping_store_relative_url );
		$grocery_shopping_store_current_theme = wp_get_theme();
		$this->grocery_shopping_store_theme_title = $grocery_shopping_store_current_theme->get( 'Name' );
		$this->grocery_shopping_store_theme_name = strtolower( preg_replace( '#[^a-zA-Z]#', '', $grocery_shopping_store_current_theme->get( 'Name' ) ) );
		$this->grocery_shopping_store_page_slug = apply_filters( $this->grocery_shopping_store_theme_name . '_theme_setup_wizard_grocery_shopping_store_page_slug', $this->grocery_shopping_store_theme_name . '-wizard' );
		$this->parent_slug = apply_filters( $this->grocery_shopping_store_theme_name . '_theme_setup_wizard_parent_slug', '' );

	}

	/**
	 * Hooks and filters
	 * @since 1.0.0
	 */
	public function init() {

		if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
			add_action( 'init', array( $this, 'get_tgmpa_instance' ), 30 );
			add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
		add_action( 'admin_init', array( $this, 'get_plugins' ), 30 );
		add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
		add_action( 'wp_ajax_setup_plugins', array( $this, 'setup_plugins' ) );
		add_action( 'wp_ajax_grocery_shopping_store_setup_widgets', array( $this, 'grocery_shopping_store_setup_widgets' ) );
		add_action( 'wp_ajax_grocery_shopping_store_setup_step', array( $this, 'grocery_shopping_store_setup_step' ) );

	}

	/**
	 * Load assets on the Getting Started page, and wherever the welcome notice is shown.
	 *
	 * @param string $grocery_shopping_store_hook Current admin page hook suffix.
	 */
	public function enqueue_scripts( $grocery_shopping_store_hook ) {
		$grocery_shopping_store_is_setup_page = ( $grocery_shopping_store_hook === $this->page_hook );
		$grocery_shopping_store_has_notice    = function_exists( 'grocery_shopping_store_show_admin_notice' ) && grocery_shopping_store_show_admin_notice();

		if ( ! $grocery_shopping_store_is_setup_page && ! $grocery_shopping_store_has_notice ) {
			return;
		}

		$grocery_shopping_store_version = wp_get_theme()->get( 'Version' );

		// The bundled Plus Jakarta Sans is Regular only, so load real weights through the local webfont loader.
		require_once get_theme_file_path( 'lib/custom/css/wptt-webfont-loader.php' );
		wp_enqueue_style( 'grocery-shopping-store-setup-fonts', grocery_shopping_store_wptt_get_webfont_url( 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap' ), array(), $grocery_shopping_store_version );
		// Version the page assets by file time so browsers and caching plugins never serve a stale copy after an update.
		$grocery_shopping_store_css = 'inc/homepage-setup/assets/css/homepage-setup-style.css';
		$grocery_shopping_store_js  = 'inc/homepage-setup/assets/js/homepage-setup-script.js';
		wp_enqueue_style( 'grocery-shopping-store-homepage-setup-style', get_theme_file_uri( $grocery_shopping_store_css ), array( 'grocery-shopping-store-setup-fonts' ), $grocery_shopping_store_version . '.' . filemtime( get_theme_file_path( $grocery_shopping_store_css ) ) );
		wp_register_script( 'grocery-shopping-store-homepage-setup-script', get_theme_file_uri( $grocery_shopping_store_js ), array( 'jquery' ), $grocery_shopping_store_version . '.' . filemtime( get_theme_file_path( $grocery_shopping_store_js ) ), true );
		wp_localize_script(
			'grocery-shopping-store-homepage-setup-script',
			'grocery_shopping_store_setup',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'wpnonce' => wp_create_nonce( 'whizzie_nonce' ),
				'i18n'    => array(
					'installing' => esc_html__( 'Working…', 'grocery-shopping-store' ),
					'active'     => esc_html__( 'Active', 'grocery-shopping-store' ),
					'failed'     => esc_html__( 'Failed', 'grocery-shopping-store' ),
					'install'    => esc_html__( 'Install & activate', 'grocery-shopping-store' ),
					'installed'  => esc_html__( 'Plugins ready', 'grocery-shopping-store' ),
					'pluginsErr' => esc_html__( 'Some plugins could not be installed. Try again, or install them from the plugins screen.', 'grocery-shopping-store' ),
					'importing'  => esc_html__( 'Importing demo…', 'grocery-shopping-store' ),
					'imported'   => esc_html__( 'Demo imported', 'grocery-shopping-store' ),
					'importErr'  => esc_html__( 'The import did not finish. Please try again.', 'grocery-shopping-store' ),
					'copied'     => esc_html__( 'Copied!', 'grocery-shopping-store' ),
				),
			)
		);
		wp_enqueue_script( 'grocery-shopping-store-homepage-setup-script' );
	}

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function tgmpa_load( $grocery_shopping_store_status ) {
		return is_admin() || current_user_can( 'install_themes' );
	}

	/**
	 * Get configured TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	 */
	public function get_tgmpa_instance() {
		$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
	}

	/**
	 * Update $tgmpa_menu_slug and $grocery_shopping_store_tgmpa_parent_slug from TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	 */
	public function set_tgmpa_url() {
		$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
		$this->tgmpa_menu_slug = apply_filters( $this->grocery_shopping_store_theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );
		$grocery_shopping_store_tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && $this->tgmpa_instance->parent_slug !== 'themes.php' ) ? 'admin.php' : 'themes.php';
		$this->tgmpa_url = apply_filters( $this->grocery_shopping_store_theme_name . '_theme_setup_wizard_tgmpa_url', $grocery_shopping_store_tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );
	}

	/**
	 * Register the Getting Started page under Appearance
	 */
	public function menu_page() {
		$this->page_hook = add_theme_page( esc_html( $this->grocery_shopping_store_page_title ), esc_html( $this->grocery_shopping_store_page_title ), 'manage_options', $this->grocery_shopping_store_page_slug, array( $this, 'wizard_page' ) );
	}

	/**
	 * Small inline SVG icons used on the page
	 *
	 * @param string $grocery_shopping_store_name Icon name.
	 */
	public function icon( $grocery_shopping_store_name ) {
		$grocery_shopping_store_icons = array(
			'check' => '<svg class="omg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.2 4.2L19 7" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'arrow' => '<svg class="omg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'close' => '<svg class="omg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
			'crown' => '<svg class="omg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 8l4.5 4L12 5l4.5 7L21 8l-2 11H5L3 8z" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>',
			'download' => '<svg class="omg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4v11M7 10l5 5 5-5M5 20h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'dash'  => '<svg class="omg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 12h10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
			'eye'   => '<svg class="omg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 9.2a2.8 2.8 0 1 0 0 5.6 2.8 2.8 0 0 0 0-5.6z" fill="none" stroke="currentColor" stroke-width="2"/></svg>',
			'book'  => '<svg class="omg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5v-15zM4 20.5A2.5 2.5 0 0 0 6.5 23H20v-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
			'table' => '<svg class="omg-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v14H4zM4 10h16M10 10v9" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
		);
		return isset( $grocery_shopping_store_icons[ $grocery_shopping_store_name ] ) ? $grocery_shopping_store_icons[ $grocery_shopping_store_name ] : '';
	}

	/**
	 * Allowed tags for printing the inline icons
	 */
	public function icon_kses() {
		return array(
			'svg'  => array( 'class' => true, 'viewbox' => true, 'aria-hidden' => true ),
			'path' => array( 'd' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true ),
		);
	}

	/**
	 * Make an interface for the Getting Started page
	 */
	public function wizard_page() {
		tgmpa_load_bulk_installer();

		if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS['tgmpa'] ) ) {
			die( esc_html__( 'Failed to find TGM', 'grocery-shopping-store' ) );
		}

		$grocery_shopping_store_url = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'whizzie-setup' );
		$grocery_shopping_store_method = '';
		$grocery_shopping_store_fields = array_keys( $_POST );

		if ( false === ( $grocery_shopping_store_creds = request_filesystem_credentials( esc_url_raw( $grocery_shopping_store_url ), $grocery_shopping_store_method, false, false, $grocery_shopping_store_fields ) ) ) {
			return true;
		}

		if ( ! WP_Filesystem( $grocery_shopping_store_creds ) ) {
			request_filesystem_credentials( esc_url_raw( $grocery_shopping_store_url ), $grocery_shopping_store_method, true, false, $grocery_shopping_store_fields );
			return true;
		}

		$grocery_shopping_store_theme = wp_get_theme();
		$grocery_shopping_store_theme_title = $grocery_shopping_store_theme->get( 'Name' );
		$grocery_shopping_store_theme_version = $grocery_shopping_store_theme->get( 'Version' );
		$grocery_shopping_store_theme_desc = wp_trim_words( $grocery_shopping_store_theme->get( 'Description' ), 28 );

		$grocery_shopping_store_steps = $this->get_steps();
		$grocery_shopping_store_progress = grocery_shopping_store_get_setup_progress();
		$grocery_shopping_store_focus = ! empty( $_GET['omg-focus'] ) && empty( $grocery_shopping_store_progress['demo'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display flag only.
		$grocery_shopping_store_done = count( array_filter( $grocery_shopping_store_progress ) );
		$grocery_shopping_store_total = count( $grocery_shopping_store_steps );

		// The first unfinished step starts open.
		$grocery_shopping_store_open = '';
		foreach ( $grocery_shopping_store_steps as $grocery_shopping_store_id => $grocery_shopping_store_step ) {
			if ( empty( $grocery_shopping_store_progress[ $grocery_shopping_store_id ] ) ) {
				$grocery_shopping_store_open = $grocery_shopping_store_id;
				break;
			}
		}

		$grocery_shopping_store_images = get_template_directory_uri() . '/inc/homepage-setup/assets/homepage-setup-images/';
		$grocery_shopping_store_check = wp_kses( $this->icon( 'check' ), $this->icon_kses() );
		$grocery_shopping_store_dash  = wp_kses( $this->icon( 'dash' ), $this->icon_kses() );
		$grocery_shopping_store_arrow = wp_kses( $this->icon( 'arrow' ), $this->icon_kses() );
		$grocery_shopping_store_crown = wp_kses( $this->icon( 'crown' ), $this->icon_kses() );
		$grocery_shopping_store_live  = '<span class="omg-live-dot" aria-hidden="true"></span>';
		?>
		<div class="wrap omg-wrap<?php echo $grocery_shopping_store_focus ? ' is-focus' : ''; ?>">

			<div class="omg-header">
				<div class="omg-header__text">
					<h1 class="omg-title">
						<?php
						/* translators: %s: theme name */
						printf( esc_html__( 'Welcome to %s', 'grocery-shopping-store' ), esc_html( $grocery_shopping_store_theme_title ) );
						?>
					</h1>
				</div>
				<div class="omg-header__actions">
					<a class="omg-btn omg-btn--demo" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_DEMO_PRO ); ?>" target="_blank"><?php echo $grocery_shopping_store_live; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?><?php esc_html_e( 'Live Demo', 'grocery-shopping-store' ); ?></a>
					<a class="omg-btn omg-btn--premium" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUY_NOW ); ?>" target="_blank"><?php echo $grocery_shopping_store_crown; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?><?php esc_html_e( 'Go To Premium', 'grocery-shopping-store' ); ?></a>
				</div>
			</div>
			<hr class="wp-header-end">

			<div class="omg-layout">

				<aside class="omg-card omg-setup<?php echo $grocery_shopping_store_done >= $grocery_shopping_store_total ? ' is-complete' : ''; ?>" id="omg-setup" data-total="<?php echo esc_attr( $grocery_shopping_store_total ); ?>">
					<div class="omg-setup__head">
						<h2 class="omg-setup__title"><?php esc_html_e( 'Site setup', 'grocery-shopping-store' ); ?></h2>
						<span class="omg-setup__count">
							<?php
							/* translators: 1: steps done, 2: total steps */
							printf( esc_html__( '%1$s of %2$s done', 'grocery-shopping-store' ), '<b class="omg-setup__done">' . esc_html( $grocery_shopping_store_done ) . '</b>', esc_html( $grocery_shopping_store_total ) );
							?>
						</span>
					</div>
					<div class="omg-progress" role="progressbar" aria-valuemin="0" aria-valuemax="<?php echo esc_attr( $grocery_shopping_store_total ); ?>" aria-valuenow="<?php echo esc_attr( $grocery_shopping_store_done ); ?>">
						<span class="omg-progress__bar" style="width: <?php echo esc_attr( max( 3, round( $grocery_shopping_store_done / $grocery_shopping_store_total * 100 ) ) ); ?>%"></span>
					</div>
					<p class="omg-setup__intro"><?php esc_html_e( 'Finish these steps to make your store look like the demo in a few minutes.', 'grocery-shopping-store' ); ?></p>

					<div class="omg-setup__complete">
						<span class="omg-setup__complete-icon"><?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></span>
						<div>
							<strong><?php esc_html_e( 'Your store is ready', 'grocery-shopping-store' ); ?></strong>
							<span><?php esc_html_e( 'All setup steps are done. Time to add your products.', 'grocery-shopping-store' ); ?></span>
						</div>
					</div>

					<ol class="omg-steps">
						<?php
						$grocery_shopping_store_number = 1;
						foreach ( $grocery_shopping_store_steps as $grocery_shopping_store_id => $grocery_shopping_store_step ) {
							$grocery_shopping_store_classes = array( 'omg-step' );
							if ( ! empty( $grocery_shopping_store_progress[ $grocery_shopping_store_id ] ) ) {
								$grocery_shopping_store_classes[] = 'is-done';
							}
							if ( $grocery_shopping_store_id === $grocery_shopping_store_open ) {
								$grocery_shopping_store_classes[] = 'is-open';
							}
							?>
							<li class="<?php echo esc_attr( implode( ' ', $grocery_shopping_store_classes ) ); ?>" data-step="<?php echo esc_attr( $grocery_shopping_store_id ); ?>">
								<button type="button" class="omg-step__head" aria-expanded="<?php echo $grocery_shopping_store_id === $grocery_shopping_store_open ? 'true' : 'false'; ?>">
									<span class="omg-step__num">
										<span class="omg-step__index"><?php echo esc_html( $grocery_shopping_store_number ); ?></span>
										<?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>
									</span>
									<span class="omg-step__text">
										<span class="omg-step__title"><?php echo esc_html( $grocery_shopping_store_step['title'] ); ?></span>
										<span class="omg-step__desc"><?php echo esc_html( $grocery_shopping_store_step['desc'] ); ?></span>
									</span>
								</button>
								<div class="omg-step__body">
									<div class="omg-step__inner">
										<?php call_user_func( array( $this, $grocery_shopping_store_step['view'] ) ); ?>
									</div>
								</div>
							</li>
							<?php
							$grocery_shopping_store_number++;
						}
						?>
					</ol>

					<p class="omg-setup__help">
						<?php esc_html_e( 'Stuck on a step?', 'grocery-shopping-store' ); ?>
						<a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_LITE_DOCS_PRO ); ?>" target="_blank"><?php esc_html_e( 'Read the setup guide', 'grocery-shopping-store' ); ?></a>
						<?php esc_html_e( 'or', 'grocery-shopping-store' ); ?>
						<a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_SUPPORT_FREE ); ?>" target="_blank"><?php esc_html_e( 'ask on the forum', 'grocery-shopping-store' ); ?></a>.
					</p>
				</aside>

				<?php if ( $grocery_shopping_store_focus ) : ?>
					<div class="omg-focus-tip" role="dialog" aria-labelledby="omg-focus-tip-title">
						<span class="omg-focus-tip__icon"><?php echo wp_kses( $this->icon( 'download' ), $this->icon_kses() ); ?></span>
						<p class="omg-kicker omg-kicker--blue"><?php esc_html_e( 'Quick setup', 'grocery-shopping-store' ); ?></p>
						<h3 class="omg-focus-tip__title" id="omg-focus-tip-title"><?php esc_html_e( 'Let’s import your demo', 'grocery-shopping-store' ); ?></h3>
						<p class="omg-focus-tip__text"><?php esc_html_e( 'Install the essential plugins, then import the demo content. The rest of this page unlocks as soon as the import finishes.', 'grocery-shopping-store' ); ?></p>
						<div class="omg-focus-tip__actions">
							<button type="button" class="omg-btn omg-btn--primary omg-focus-ok"><?php esc_html_e( 'Got it', 'grocery-shopping-store' ); ?></button>
							<button type="button" class="omg-skip omg-focus-exit"><?php esc_html_e( 'Skip, show everything', 'grocery-shopping-store' ); ?></button>
						</div>
					</div>
				<?php endif; ?>

				<div class="omg-main">

					<section class="omg-card omg-hero">
						<div class="omg-hero__content">
							<div class="omg-badges">
								<span class="omg-badge omg-badge--blue"><?php esc_html_e( 'Active theme', 'grocery-shopping-store' ); ?></span>
								<span class="omg-badge"><?php esc_html_e( 'Free', 'grocery-shopping-store' ); ?></span>
							</div>
							<h2 class="omg-hero__title"><?php echo esc_html( $grocery_shopping_store_theme_title ); ?></h2>
							<p class="omg-hero__desc"><?php echo esc_html( $grocery_shopping_store_theme_desc ); ?></p>
							<div class="omg-hero__actions">
								<a class="omg-btn omg-btn--primary" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize theme', 'grocery-shopping-store' ); ?></a>
								<a class="omg-btn omg-btn--outline" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank"><?php esc_html_e( 'View your site', 'grocery-shopping-store' ); ?></a>
								<a class="omg-btn omg-btn--pink" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUY_NOW ); ?>" target="_blank"><?php echo $grocery_shopping_store_crown; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?><?php esc_html_e( 'Upgrade to Pro', 'grocery-shopping-store' ); ?></a>
								<a class="omg-btn omg-btn--dark" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUNDLE_BUTTON ); ?>" target="_blank"><?php esc_html_e( 'Get the Bundle', 'grocery-shopping-store' ); ?></a>
							</div>
						</div>
						<div class="omg-hero__media">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>" alt="<?php echo esc_attr( $grocery_shopping_store_theme_title ); ?>">
						</div>
					</section>

					<div class="omg-promos">
						<section class="omg-card omg-pro">
							<span class="omg-pro__ribbon"><?php esc_html_e( '25% Off', 'grocery-shopping-store' ); ?></span>
							<p class="omg-kicker omg-kicker--pink"><?php esc_html_e( 'Go Pro', 'grocery-shopping-store' ); ?></p>
							<h3 class="omg-pro__title">
								<?php
								/* translators: %s: theme name */
								printf( esc_html__( 'Unlock %s Pro', 'grocery-shopping-store' ), esc_html( $grocery_shopping_store_theme_title ) );
								?>
							</h3>
							<div class="omg-pro__links">
								<?php
								$grocery_shopping_store_pro_links = array(
									array( GROCERY_SHOPPING_STORE_DEMO_PRO, 'eye', __( 'Live demo', 'grocery-shopping-store' ), true ),
									array( GROCERY_SHOPPING_STORE_DOCS_PRO, 'book', __( 'Pro docs', 'grocery-shopping-store' ), true ),
									array( '#omg-compare', 'table', __( 'Free vs Pro', 'grocery-shopping-store' ), false ),
								);
								foreach ( $grocery_shopping_store_pro_links as $grocery_shopping_store_pro_link ) {
									?>
									<a class="omg-pro__link" href="<?php echo esc_url( $grocery_shopping_store_pro_link[0] ); ?>"<?php echo $grocery_shopping_store_pro_link[3] ? ' target="_blank"' : ''; ?>>
										<?php echo wp_kses( $this->icon( $grocery_shopping_store_pro_link[1] ), $this->icon_kses() ); ?>
										<span><?php echo esc_html( $grocery_shopping_store_pro_link[2] ); ?></span>
									</a>
									<?php
								}
								?>
							</div>
							<ul class="omg-checklist">
								<li><?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?><?php esc_html_e( 'All premium demo layouts', 'grocery-shopping-store' ); ?></li>
								<li><?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?><?php esc_html_e( 'Advanced customizer controls', 'grocery-shopping-store' ); ?></li>
								<li><?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?><?php esc_html_e( 'Priority support & updates', 'grocery-shopping-store' ); ?></li>
							</ul>
							<div class="omg-code">
								<span class="omg-code__label"><?php esc_html_e( 'Code', 'grocery-shopping-store' ); ?></span>
								<code class="omg-code__value">NYTHEMES25</code>
								<button type="button" class="omg-code__copy" data-omg-copy="NYTHEMES25"><?php esc_html_e( 'Copy', 'grocery-shopping-store' ); ?></button>
							</div>
							<p class="omg-pro__price">
								<strong>$39</strong>
								<span><?php esc_html_e( 'Pro license · save 25% with the code above', 'grocery-shopping-store' ); ?></span>
							</p>
							<a class="omg-btn omg-btn--pink omg-btn--block" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUY_NOW ); ?>" target="_blank"><?php esc_html_e( 'Upgrade to Pro — just $39', 'grocery-shopping-store' ); ?></a>
						</section>

						<section class="omg-card omg-bundle omg-glow-border">
							<div class="omg-bundle__head">
								<div>
									<p class="omg-kicker omg-kicker--pink"><?php esc_html_e( 'Best value', 'grocery-shopping-store' ); ?></p>
									<h3 class="omg-bundle__title"><?php esc_html_e( 'WP Theme Bundle', 'grocery-shopping-store' ); ?></h3>
								</div>
								<span class="omg-bundle__seal"><small><?php esc_html_e( 'Extra', 'grocery-shopping-store' ); ?></small><b>15%</b><small><?php esc_html_e( 'Off', 'grocery-shopping-store' ); ?></small></span>
							</div>
							<div class="omg-bundle__media">
								<img src="<?php echo esc_url( $grocery_shopping_store_images . 'bundle-banner.png' ); ?>" alt="<?php esc_attr_e( 'Theme bundle collage', 'grocery-shopping-store' ); ?>">
							</div>
							<p class="omg-bundle__desc"><?php esc_html_e( 'Every Omega WordPress theme in one download.', 'grocery-shopping-store' ); ?></p>
							<ul class="omg-checklist">
								<li><?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?><?php esc_html_e( 'All premium Omega themes included', 'grocery-shopping-store' ); ?></li>
								<li><?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?><?php esc_html_e( 'Updates for every theme in the bundle', 'grocery-shopping-store' ); ?></li>
								<li><?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?><?php esc_html_e( 'Priority support on all themes', 'grocery-shopping-store' ); ?></li>
							</ul>
							<p class="omg-price"><del>$2795</del><strong>$69</strong></p>
							<div class="omg-code omg-code--light">
								<span class="omg-code__label"><?php esc_html_e( 'Code', 'grocery-shopping-store' ); ?></span>
								<code class="omg-code__value">BNDL15OFF</code>
								<button type="button" class="omg-code__copy" data-omg-copy="BNDL15OFF"><?php esc_html_e( 'Copy', 'grocery-shopping-store' ); ?></button>
							</div>
							<a class="omg-btn omg-btn--dark omg-btn--block" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUNDLE_BUTTON ); ?>" target="_blank"><?php esc_html_e( 'Shop the bundle', 'grocery-shopping-store' ); ?></a>
						</section>
					</div>

					<div class="omg-info">
						<section class="omg-card omg-compare" id="omg-compare">
							<h3 class="omg-card__title"><?php esc_html_e( 'Free vs Pro', 'grocery-shopping-store' ); ?></h3>
							<table class="omg-compare__table">
								<thead>
									<tr>
										<th><?php esc_html_e( 'Feature', 'grocery-shopping-store' ); ?></th>
										<th><?php esc_html_e( 'Free', 'grocery-shopping-store' ); ?></th>
										<th class="omg-compare__pro"><?php esc_html_e( 'Pro', 'grocery-shopping-store' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$grocery_shopping_store_features = array(
										array( __( 'One-click demo import', 'grocery-shopping-store' ), true ),
										array( __( 'WooCommerce shop pages', 'grocery-shopping-store' ), true ),
										array( __( 'Responsive & RTL ready', 'grocery-shopping-store' ), true ),
										array( __( 'Premium demo layouts', 'grocery-shopping-store' ), false ),
										array( __( 'Advanced customizer options', 'grocery-shopping-store' ), false ),
										array( __( 'Priority support', 'grocery-shopping-store' ), false ),
									);
									foreach ( $grocery_shopping_store_features as $grocery_shopping_store_feature ) {
										?>
										<tr>
											<td><?php echo esc_html( $grocery_shopping_store_feature[0] ); ?></td>
											<td class="<?php echo $grocery_shopping_store_feature[1] ? 'omg-yes' : 'omg-no'; ?>"><?php echo $grocery_shopping_store_feature[1] ? $grocery_shopping_store_check : $grocery_shopping_store_dash; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></td>
											<td class="omg-yes"><?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
							<a class="omg-btn omg-btn--demo omg-btn--block omg-compare__demo" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_DEMO_PRO ); ?>" target="_blank"><?php echo $grocery_shopping_store_live; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?><?php esc_html_e( 'See the Pro demo', 'grocery-shopping-store' ); ?> <?php echo $grocery_shopping_store_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></a>
						</section>

						<section class="omg-card omg-links">
							<h3 class="omg-card__title"><?php esc_html_e( 'Important links', 'grocery-shopping-store' ); ?></h3>
							<div class="omg-links__grid">
								<?php
								$grocery_shopping_store_links = array(
									array( GROCERY_SHOPPING_STORE_LITE_DOCS_PRO, __( 'Free documentation', 'grocery-shopping-store' ), __( 'Step-by-step setup guides', 'grocery-shopping-store' ), '' ),
									array( GROCERY_SHOPPING_STORE_SUPPORT_FREE, __( 'Support forum', 'grocery-shopping-store' ), __( 'Ask the community & our team', 'grocery-shopping-store' ), '' ),
									array( GROCERY_SHOPPING_STORE_DEMO_PRO, __( 'Premium demo', 'grocery-shopping-store' ), __( 'See every Pro layout live', 'grocery-shopping-store' ), 'demo' ),
									array( GROCERY_SHOPPING_STORE_BUY_NOW, __( 'Get Premium', 'grocery-shopping-store' ), __( 'Compare plans & pricing', 'grocery-shopping-store' ), 'premium' ),
								);
								foreach ( $grocery_shopping_store_links as $grocery_shopping_store_link ) {
									?>
									<a class="omg-links__item<?php echo $grocery_shopping_store_link[3] ? ' omg-links__item--' . esc_attr( $grocery_shopping_store_link[3] ) : ''; ?>" href="<?php echo esc_url( $grocery_shopping_store_link[0] ); ?>" target="_blank">
										<strong>
											<?php echo esc_html( $grocery_shopping_store_link[1] ); ?>
											<?php if ( 'demo' === $grocery_shopping_store_link[3] ) : ?>
												<em class="omg-tag omg-tag--live"><?php echo $grocery_shopping_store_live; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?><?php esc_html_e( 'Live', 'grocery-shopping-store' ); ?></em>
											<?php elseif ( 'premium' === $grocery_shopping_store_link[3] ) : ?>
												<em class="omg-tag omg-tag--pro"><?php esc_html_e( 'Pro', 'grocery-shopping-store' ); ?></em>
											<?php endif; ?>
										</strong>
										<span><?php echo esc_html( $grocery_shopping_store_link[2] ); ?></span>
									</a>
									<?php
								}
								?>
							</div>
							<div class="omg-expert">
								<div class="omg-expert__content">
									<h3 class="omg-expert__title"><?php esc_html_e( 'Want us to set it up for you?', 'grocery-shopping-store' ); ?></h3>
									<p class="omg-expert__desc"><?php esc_html_e( 'Our team can install the theme, add your products and match your brand.', 'grocery-shopping-store' ); ?></p>
									<ul class="omg-expert__list">
										<li><?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?><?php esc_html_e( 'Theme & plugin setup', 'grocery-shopping-store' ); ?></li>
										<li><?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?><?php esc_html_e( 'Demo content import', 'grocery-shopping-store' ); ?></li>
										<li><?php echo $grocery_shopping_store_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?><?php esc_html_e( 'Product upload', 'grocery-shopping-store' ); ?></li>
									</ul>
								</div>
								<a class="omg-btn omg-btn--primary omg-expert__btn" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_HIRE_EXPERT ); ?>" target="_blank"><?php esc_html_e( 'Hire an expert', 'grocery-shopping-store' ); ?></a>
							</div>
						</section>
					</div>

				</div> <!-- .omg-main -->
			</div> <!-- .omg-layout -->

			<div class="omg-footer">
				<p>
					<?php esc_html_e( 'Enjoying the theme?', 'grocery-shopping-store' ); ?>
					<a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_REVIEW_FREE ); ?>" target="_blank"><?php esc_html_e( 'Leave a ★★★★★ review', 'grocery-shopping-store' ); ?></a>
				</p>
				<p>
					<?php
					/* translators: %s: theme version */
					printf( esc_html__( 'Version %s', 'grocery-shopping-store' ), esc_html( $grocery_shopping_store_theme_version ) );
					?>
				</p>
			</div>
		</div> <!-- .omg-wrap -->
		<?php
	}


	/**
	 * Set options for the steps
	 * Incorporate any options set by the theme dev
	 * Return the array for the steps
	 * @return Array
	 */
	public function get_steps() {
		$grocery_shopping_store_dev_steps = $this->config_steps;
		$grocery_shopping_store_steps = array(
			'plugins' => array(
				'id'    => 'plugins',
				'title' => __( 'Install essential plugins', 'grocery-shopping-store' ),
				'desc'  => __( 'Recommended for demo layouts and shop pages', 'grocery-shopping-store' ),
				'view'  => 'get_step_plugins',
			),
			'demo' => array(
				'id'    => 'demo',
				'title' => __( 'Import demo content', 'grocery-shopping-store' ),
				'desc'  => __( 'Pages, products, menus and widgets', 'grocery-shopping-store' ),
				'view'  => 'get_step_demo',
			),
			'customize' => array(
				'id'    => 'customize',
				'title' => __( 'Make it yours', 'grocery-shopping-store' ),
				'desc'  => __( 'Logo, colors, header and homepage', 'grocery-shopping-store' ),
				'view'  => 'get_step_customize',
			),
			'launch' => array(
				'id'    => 'launch',
				'title' => __( 'Launch your store', 'grocery-shopping-store' ),
				'desc'  => __( 'Preview and go live', 'grocery-shopping-store' ),
				'view'  => 'get_step_launch',
			),
		);

		// Iterate through each step and replace with dev config values
		if( $grocery_shopping_store_dev_steps ) {
			// Configurable elements - these are the only ones the dev can update from homepage-setup-settings.php
			$grocery_shopping_store_can_config = array( 'title', 'desc' );
			foreach( $grocery_shopping_store_dev_steps as $grocery_shopping_store_dev_step ) {
				// We can only proceed if an ID exists and matches one of our IDs
				if( isset( $grocery_shopping_store_dev_step['id'] ) ) {
					$grocery_shopping_store_id = $grocery_shopping_store_dev_step['id'];
					if( isset( $grocery_shopping_store_steps[$grocery_shopping_store_id] ) ) {
						foreach( $grocery_shopping_store_can_config as $grocery_shopping_store_element ) {
							if( isset( $grocery_shopping_store_dev_step[$grocery_shopping_store_element] ) ) {
								$grocery_shopping_store_steps[$grocery_shopping_store_id][$grocery_shopping_store_element] = $grocery_shopping_store_dev_step[$grocery_shopping_store_element];
							}
						}
					}
				}
			}
		}
		return $grocery_shopping_store_steps;
	}

	/**
	 * Print the content for the plugins step
	 */
	public function get_step_plugins() {
		$grocery_shopping_store_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$grocery_shopping_store_pending = 0;

		// WooCommerce goes first: the other plugins need it active before they can activate.
		$grocery_shopping_store_plugins = $grocery_shopping_store_instance->plugins;
		if ( isset( $grocery_shopping_store_plugins['woocommerce'] ) ) {
			$grocery_shopping_store_plugins = array( 'woocommerce' => $grocery_shopping_store_plugins['woocommerce'] ) + $grocery_shopping_store_plugins;
		}
		?>
		<ul class="omg-plugins">
			<?php
			foreach ( $grocery_shopping_store_plugins as $grocery_shopping_store_slug => $grocery_shopping_store_plugin ) {
				$grocery_shopping_store_is_ready = $grocery_shopping_store_instance->is_plugin_active( $grocery_shopping_store_slug ) && false === $grocery_shopping_store_instance->does_plugin_have_update( $grocery_shopping_store_slug );
				$grocery_shopping_store_is_required = ! empty( $grocery_shopping_store_plugin['required'] );
				if ( ! $grocery_shopping_store_is_ready ) {
					$grocery_shopping_store_pending++;
				}
				if ( $grocery_shopping_store_is_ready ) {
					$grocery_shopping_store_badge = __( 'Active', 'grocery-shopping-store' );
				} elseif ( $grocery_shopping_store_is_required ) {
					$grocery_shopping_store_badge = __( 'Required', 'grocery-shopping-store' );
				} else {
					$grocery_shopping_store_badge = __( 'Optional', 'grocery-shopping-store' );
				}
				?>
				<li class="omg-plugin<?php echo $grocery_shopping_store_is_ready ? ' is-ready' : ''; ?>" data-slug="<?php echo esc_attr( $grocery_shopping_store_slug ); ?>">
					<label class="omg-plugin__label">
						<input type="checkbox" class="omg-plugin__check" value="<?php echo esc_attr( $grocery_shopping_store_slug ); ?>" checked <?php disabled( $grocery_shopping_store_is_ready || $grocery_shopping_store_is_required ); ?>>
						<span class="omg-plugin__name"><?php echo esc_html( $grocery_shopping_store_plugin['name'] ); ?></span>
					</label>
					<span class="omg-plugin__status omg-plugin__status--<?php echo $grocery_shopping_store_is_ready ? 'active' : ( $grocery_shopping_store_is_required ? 'required' : 'optional' ); ?>"><?php echo esc_html( $grocery_shopping_store_badge ); ?></span>
				</li>
				<?php
			}
			?>
		</ul>
		<p class="omg-step__error" hidden></p>
		<?php if ( $grocery_shopping_store_pending ) : ?>
			<button type="button" class="omg-btn omg-btn--primary omg-btn--block omg-install-plugins">
				<span class="omg-btn__label"><?php esc_html_e( 'Install & activate', 'grocery-shopping-store' ); ?></span>
				<span class="omg-btn__count">(<?php echo esc_html( $grocery_shopping_store_pending ); ?>)</span>
			</button>
			<button type="button" class="omg-skip" data-omg-complete="plugins"><?php esc_html_e( 'Skip this step', 'grocery-shopping-store' ); ?></button>
		<?php else : ?>
			<p class="omg-step__note"><?php esc_html_e( 'All recommended plugins are installed and active.', 'grocery-shopping-store' ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Print the content for the demo import step
	 */
	public function get_step_demo() {
		?>
		<p class="omg-step__note"><?php esc_html_e( 'Creates the homepage, inner pages, sample posts and products and the primary menu.', 'grocery-shopping-store' ); ?></p>
		<p class="omg-step__error" hidden></p>
		<button type="button" class="omg-btn omg-btn--primary omg-btn--block omg-import-demo">
			<span class="omg-btn__label"><?php esc_html_e( 'Import demo content', 'grocery-shopping-store' ); ?></span>
		</button>
		<?php
	}

	/**
	 * Print the content for the customize step
	 */
	public function get_step_customize() {
		$grocery_shopping_store_targets = array(
			array( 'section', 'title_tagline', __( 'Logo & title', 'grocery-shopping-store' ) ),
			array( 'panel', 'grocery_shopping_store_theme_colors_panel', __( 'Colors', 'grocery-shopping-store' ) ),
			array( 'section', 'grocery_shopping_store_button_header_setting', __( 'Header', 'grocery-shopping-store' ) ),
			array( 'panel', 'grocery_shopping_store_theme_home_pannel', __( 'Homepage', 'grocery-shopping-store' ) ),
		);
		?>
		<div class="omg-chips">
			<?php foreach ( $grocery_shopping_store_targets as $grocery_shopping_store_target ) : ?>
				<a class="omg-chip" href="<?php echo esc_url( add_query_arg( 'autofocus[' . $grocery_shopping_store_target[0] . ']', $grocery_shopping_store_target[1], admin_url( 'customize.php' ) ) ); ?>" target="_blank"><?php echo esc_html( $grocery_shopping_store_target[2] ); ?></a>
			<?php endforeach; ?>
		</div>
		<button type="button" class="omg-btn omg-btn--primary omg-btn--block" data-omg-complete="customize"><?php esc_html_e( 'Mark as done', 'grocery-shopping-store' ); ?></button>
		<?php
	}

	/**
	 * Print the content for the final step
	 */
	public function get_step_launch() {
		?>
		<p class="omg-step__note"><?php esc_html_e( 'Check your store on desktop and mobile, then share it with your customers.', 'grocery-shopping-store' ); ?></p>
		<a class="omg-btn omg-btn--primary omg-btn--block" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" data-omg-complete="launch"><?php esc_html_e( 'View your site', 'grocery-shopping-store' ); ?></a>
		<?php
	}

	/**
	 * Get the plugins registered with TGMPA
	 */
	public function get_plugins() {
		$grocery_shopping_store_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$grocery_shopping_store_plugins = array(
			'all' 		=> array(),
			'install'	=> array(),
			'update'	=> array(),
			'activate'	=> array()
		);
		foreach( $grocery_shopping_store_instance->plugins as $grocery_shopping_store_slug=>$grocery_shopping_store_plugin ) {
			if( $grocery_shopping_store_instance->is_plugin_active( $grocery_shopping_store_slug ) && false === $grocery_shopping_store_instance->does_plugin_have_update( $grocery_shopping_store_slug ) ) {
				// Plugin is installed and up to date
				continue;
			} else {
				$grocery_shopping_store_plugins['all'][$grocery_shopping_store_slug] = $grocery_shopping_store_plugin;
				if( ! $grocery_shopping_store_instance->is_plugin_installed( $grocery_shopping_store_slug ) ) {
					$grocery_shopping_store_plugins['install'][$grocery_shopping_store_slug] = $grocery_shopping_store_plugin;
				} else {
					if( false !== $grocery_shopping_store_instance->does_plugin_have_update( $grocery_shopping_store_slug ) ) {
						$grocery_shopping_store_plugins['update'][$grocery_shopping_store_slug] = $grocery_shopping_store_plugin;
					}
					if( $grocery_shopping_store_instance->can_plugin_activate( $grocery_shopping_store_slug ) ) {
						$grocery_shopping_store_plugins['activate'][$grocery_shopping_store_slug] = $grocery_shopping_store_plugin;
					}
				}
			}
		}
		return $grocery_shopping_store_plugins;
	}

	/**
	 * Mark a setup step as done
	 */
	public function grocery_shopping_store_setup_step() {
		check_ajax_referer( 'whizzie_nonce', 'wpnonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}
		$grocery_shopping_store_step = isset( $_POST['step'] ) ? sanitize_key( wp_unslash( $_POST['step'] ) ) : '';
		grocery_shopping_store_set_setup_step_done( $grocery_shopping_store_step );
		wp_send_json_success( grocery_shopping_store_get_setup_progress() );
	}

	public function setup_plugins() {
		if ( ! check_ajax_referer( 'whizzie_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) || ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No Slug Found','grocery-shopping-store' ) ) );
		}
		$grocery_shopping_store_json = array();
		// send back some json we use to hit up TGM
		$grocery_shopping_store_plugins = $this->get_plugins();

		// what are we doing with this plugin?
		foreach ( $grocery_shopping_store_plugins['activate'] as $grocery_shopping_store_slug => $grocery_shopping_store_plugin ) {
			if ( $_POST['slug'] == $grocery_shopping_store_slug ) {
				$grocery_shopping_store_json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $grocery_shopping_store_slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( 'Activating…','grocery-shopping-store' ),
				);
				break;
			}
		}
		foreach ( $grocery_shopping_store_plugins['update'] as $grocery_shopping_store_slug => $grocery_shopping_store_plugin ) {
			if ( $_POST['slug'] == $grocery_shopping_store_slug ) {
				$grocery_shopping_store_json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $grocery_shopping_store_slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html__( 'Updating…','grocery-shopping-store' ),
				);
				break;
			}
		}
		foreach ( $grocery_shopping_store_plugins['install'] as $grocery_shopping_store_slug => $grocery_shopping_store_plugin ) {
			if ( $_POST['slug'] == $grocery_shopping_store_slug ) {
				$grocery_shopping_store_json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $grocery_shopping_store_slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( 'Installing…','grocery-shopping-store' ),
				);
				break;
			}
		}
		if ( $grocery_shopping_store_json ) {
			$grocery_shopping_store_json['hash'] = md5( serialize( $grocery_shopping_store_json ) ); // used for checking if duplicates happen, move to next plugin
			wp_send_json( $grocery_shopping_store_json );
		} else {
			wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Active','grocery-shopping-store' ) ) );
		}
		exit;
	}


	public function grocery_shopping_store_customizer_nav_menu() {

		/* -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+- Grocery Shopping Store Primary Menu -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-*/

		$grocery_shopping_store_themename = 'Grocery Shopping Store';
		$grocery_shopping_store_menuname = $grocery_shopping_store_themename . ' Primary Menu';
		$grocery_shopping_store_menulocation = 'grocery-shopping-store-primary-menu';
		$grocery_shopping_store_menu_exists = wp_get_nav_menu_object($grocery_shopping_store_menuname);

		if (!$grocery_shopping_store_menu_exists) {
			$grocery_shopping_store_menu_id = wp_create_nav_menu($grocery_shopping_store_menuname);

			// Home
			wp_update_nav_menu_item($grocery_shopping_store_menu_id, 0, array(
				'menu-item-title' => __('Home', 'grocery-shopping-store'),
				'menu-item-classes' => 'home',
				'menu-item-url' => home_url('/'),
				'menu-item-status' => 'publish'
			));

			// About
			$grocery_shopping_store_page_about = get_page_by_path('about');
			if($grocery_shopping_store_page_about){
				wp_update_nav_menu_item($grocery_shopping_store_menu_id, 0, array(
					'menu-item-title' => __('About', 'grocery-shopping-store'),
					'menu-item-classes' => 'about',
					'menu-item-url' => get_permalink($grocery_shopping_store_page_about),
					'menu-item-status' => 'publish'
				));
			}

			// Services
			$grocery_shopping_store_page_services = get_page_by_path('services');
			if($grocery_shopping_store_page_services){
				wp_update_nav_menu_item($grocery_shopping_store_menu_id, 0, array(
					'menu-item-title' => __('Services', 'grocery-shopping-store'),
					'menu-item-classes' => 'services',
					'menu-item-url' => get_permalink($grocery_shopping_store_page_services),
					'menu-item-status' => 'publish'
				));
			}

			// Shop Page (WooCommerce)
			if (class_exists('WooCommerce')) {
				$grocery_shopping_store_shop_page_id = wc_get_page_id('shop');
				if ($grocery_shopping_store_shop_page_id) {
					wp_update_nav_menu_item($grocery_shopping_store_menu_id, 0, array(
						'menu-item-title' => __('Shop', 'grocery-shopping-store'),
						'menu-item-classes' => 'shop',
						'menu-item-url' => get_permalink($grocery_shopping_store_shop_page_id),
						'menu-item-status' => 'publish'
					));
				}
			}

			// Blog
			$grocery_shopping_store_page_blog = get_page_by_path('blog');
			if($grocery_shopping_store_page_blog){
				wp_update_nav_menu_item($grocery_shopping_store_menu_id, 0, array(
					'menu-item-title' => __('Blog', 'grocery-shopping-store'),
					'menu-item-classes' => 'blog',
					'menu-item-url' => get_permalink($grocery_shopping_store_page_blog),
					'menu-item-status' => 'publish'
				));
			}

			// 404 Page
			$grocery_shopping_store_notfound = get_page_by_path('404 Page');
			if($grocery_shopping_store_notfound){
				wp_update_nav_menu_item($grocery_shopping_store_menu_id, 0, array(
					'menu-item-title' => __('404 Page', 'grocery-shopping-store'),
					'menu-item-classes' => '404',
					'menu-item-url' => get_permalink($grocery_shopping_store_notfound),
					'menu-item-status' => 'publish'
				));
			}

			if (!has_nav_menu($grocery_shopping_store_menulocation)) {
				$grocery_shopping_store_locations = get_theme_mod( 'nav_menu_locations', array() );
				$grocery_shopping_store_locations[$grocery_shopping_store_menulocation] = $grocery_shopping_store_menu_id;
				set_theme_mod('nav_menu_locations', $grocery_shopping_store_locations);
			}
		}
	}

	
	/**
	 * Imports the Demo Content
	 * @since 1.1.0
	 */
	public function grocery_shopping_store_setup_widgets(){

		check_ajax_referer( 'whizzie_nonce', 'wpnonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		/* -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+- MENUS PAGES -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-*/
		
			// Creation of home page //
			$grocery_shopping_store_home_content = '';
			$grocery_shopping_store_home_title = 'Home';
			$grocery_shopping_store_home = array(
					'post_type' => 'page',
					'post_title' => $grocery_shopping_store_home_title,
					'post_content'  => $grocery_shopping_store_home_content,
					'post_status' => 'publish',
					'post_author' => 1,
					'post_slug' => 'home'
			);
			$grocery_shopping_store_home_id = wp_insert_post($grocery_shopping_store_home);

			add_post_meta( $grocery_shopping_store_home_id, '_wp_page_template', 'frontpage.php' );

			$grocery_shopping_store_home = get_page_by_path( 'Home' );
			update_option( 'page_on_front', $grocery_shopping_store_home->ID );
			update_option( 'show_on_front', 'page' );

			// Creation of blog page //
			$grocery_shopping_store_blog_title = 'Blog';
			$grocery_shopping_store_blog_check = get_page_by_path('blog');
			if (!$grocery_shopping_store_blog_check) {
				$grocery_shopping_store_blog = array(
					'post_type'    => 'page',
					'post_title'   => $grocery_shopping_store_blog_title,
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_name'    => 'blog'
				);
				$grocery_shopping_store_blog_id = wp_insert_post($grocery_shopping_store_blog);

				if (!is_wp_error($grocery_shopping_store_blog_id)) {
					update_option('page_for_posts', $grocery_shopping_store_blog_id);
				}
			}

			// Creation of about page //
			$grocery_shopping_store_about_title = 'About';
			$grocery_shopping_store_about_content = 'What is Lorem Ipsum?
														Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
														&nbsp;
														Why do we use it?
														It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
														&nbsp;
														Where does it come from?
														There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which dont look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isnt anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
														&nbsp;
														Why do we use it?
														It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
														&nbsp;
														Where does it come from?
														There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which dont look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isnt anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
			$grocery_shopping_store_about_check = get_page_by_path('about');
			if (!$grocery_shopping_store_about_check) {
				$grocery_shopping_store_about = array(
					'post_type'    => 'page',
					'post_title'   => $grocery_shopping_store_about_title,
					'post_content'   => $grocery_shopping_store_about_content,
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_name'    => 'about'
				);
				wp_insert_post($grocery_shopping_store_about);
			}

			// Creation of services page //
			$grocery_shopping_store_services_title = 'Services';
			$grocery_shopping_store_services_content = 'What is Lorem Ipsum?
														Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
														&nbsp;
														Why do we use it?
														It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
														&nbsp;
														Where does it come from?
														There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which dont look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isnt anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
														&nbsp;
														Why do we use it?
														It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
														&nbsp;
														Where does it come from?
														There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which dont look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isnt anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
			$grocery_shopping_store_services_check = get_page_by_path('services');
			if (!$grocery_shopping_store_services_check) {
				$grocery_shopping_store_services = array(
					'post_type'    => 'page',
					'post_title'   => $grocery_shopping_store_services_title,
					'post_content'   => $grocery_shopping_store_services_content,
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_name'    => 'services'
				);
				wp_insert_post($grocery_shopping_store_services);
			}

			// Creation of 404 page //
			$grocery_shopping_store_notfound_title = '404 Page';
			$grocery_shopping_store_notfound = array(
				'post_type'   => 'page',
				'post_title'  => $grocery_shopping_store_notfound_title,
				'post_status' => 'publish',
				'post_author' => 1,
				'post_slug'   => '404'
			);
			$grocery_shopping_store_notfound_id = wp_insert_post($grocery_shopping_store_notfound);
			add_post_meta($grocery_shopping_store_notfound_id, '_wp_page_template', '404.php');



		/* -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+- PRODUCTS -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-*/
			// Products need WooCommerce; skip them if the plugin step was skipped.
			if ( taxonomy_exists( 'product_cat' ) ) {
			
			$grocery_shopping_store_uncategorized_term = get_term_by('name', 'Uncategorized', 'product_cat');
			if ($grocery_shopping_store_uncategorized_term) {
				wp_delete_term($grocery_shopping_store_uncategorized_term->term_id, 'product_cat');
			}

			$grocery_shopping_store_product_category= array(
				'Grocery' => array(
							'Apple',
							'Tomato',
							'Strawberry'
				)
			);
			$grocery_shopping_store_i = 1;
			foreach ( $grocery_shopping_store_product_category as $grocery_shopping_store_product_cats => $grocery_shopping_store_products_name ) {

				// Insert porduct cats Start
				$grocery_shopping_store_content = 'Lorem ipsum dolor sit amet';
				$grocery_shopping_store_parent_category	=	wp_insert_term(
				$grocery_shopping_store_product_cats, // the term
				'product_cat', // the taxonomy
				array(
					'description'=> $grocery_shopping_store_content,
					'slug' => 'product_cat'.$grocery_shopping_store_i
				));
				// The category already exists (demo imported before): reuse it.
				if ( is_wp_error( $grocery_shopping_store_parent_category ) ) {
					$grocery_shopping_store_parent_category = array( 'term_id' => (int) $grocery_shopping_store_parent_category->get_error_data( 'term_exists' ) );
				}

				$grocery_shopping_store_image_url = get_template_directory_uri().'/inc/homepage-setup/assets/homepage-setup-images/Tomato.png';

				$grocery_shopping_store_image_name= 'Tomato.png';
				$upload_dir       = wp_upload_dir();
				// Set upload folder
				$grocery_shopping_store_image_data= file_get_contents($grocery_shopping_store_image_url);
				// Get image data
				$grocery_shopping_store_unique_file_name = wp_unique_filename( $upload_dir['path'], $grocery_shopping_store_image_name );
				// Generate unique name
				$grocery_shopping_store_filename= basename( $grocery_shopping_store_unique_file_name );
				// Create image file name

				// Check folder permission and define file location
				if( wp_mkdir_p( $upload_dir['path'] ) ) {
				$file = $upload_dir['path'] . '/' . $grocery_shopping_store_filename;
				} else {
				$file = $upload_dir['basedir'] . '/' . $grocery_shopping_store_filename;
				}

				// Create the image  file on the server
				if ( ! function_exists( 'WP_Filesystem' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				
				WP_Filesystem();
				global $wp_filesystem;
				
				if ( ! $wp_filesystem->put_contents( $file, $grocery_shopping_store_image_data, FS_CHMOD_FILE ) ) {
					wp_die( 'Error saving file!' );
				}
				
				// Check image file type
				$wp_filetype = wp_check_filetype( $grocery_shopping_store_filename, null );

				// Set attachment data
				$grocery_shopping_store_attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => sanitize_file_name( $grocery_shopping_store_filename ),
				'post_content'   => '',
				'post_type'     => 'product',
				'post_status'    => 'inherit'
				);

				// Create the attachment
				$grocery_shopping_store_attach_id = wp_insert_attachment( $grocery_shopping_store_attachment, $file, 0 );

				// Include image.php
				require_once(ABSPATH . 'wp-admin/includes/image.php');

				// Define attachment metadata
				$grocery_shopping_store_attach_data = wp_generate_attachment_metadata( $grocery_shopping_store_attach_id, $file );

				// Assign metadata to attachment
				wp_update_attachment_metadata( $grocery_shopping_store_attach_id, $grocery_shopping_store_attach_data );

				update_term_meta( $grocery_shopping_store_parent_category['term_id'], 'thumbnail_id', $grocery_shopping_store_attach_id );

				// create Product START
				foreach ( $grocery_shopping_store_products_name as $key => $grocery_shopping_store_product_title ) {

					$grocery_shopping_store_content = 'Te obtinuit ut adepto satis somno.';
					// Create post object
					$my_post = array(
						'post_title'    => wp_strip_all_tags( $grocery_shopping_store_product_title ),
						'post_content'  => $grocery_shopping_store_content,
						'post_status'   => 'publish',
						'post_type'     => 'product',
					);

					// Insert the post into the database
					$post_id    = wp_insert_post($my_post);

					wp_set_object_terms( $post_id, 'product_cat' . $grocery_shopping_store_i, 'product_cat', true );

					update_post_meta($post_id, '_regular_price', '22');
					update_post_meta($post_id, '_sale_price', '19');
					update_post_meta($post_id, '_price', '19');

					// Now replace meta w/ new updated value array
					$grocery_shopping_store_image_url = get_template_directory_uri().'/inc/homepage-setup/assets/homepage-setup-images/'.str_replace( " ", "-", $grocery_shopping_store_product_title).'.png';

					echo $grocery_shopping_store_image_url . "<br>";

					$grocery_shopping_store_image_name       = $grocery_shopping_store_product_title.'.png';
					$upload_dir = wp_upload_dir();
					// Set upload folder
					$grocery_shopping_store_image_data = file_get_contents(esc_url($grocery_shopping_store_image_url));

					// Get image data
					$grocery_shopping_store_unique_file_name = wp_unique_filename($upload_dir['path'], $grocery_shopping_store_image_name);
					// Generate unique name
					$grocery_shopping_store_filename = basename($grocery_shopping_store_unique_file_name);
					// Create image file name

					// Check folder permission and define file location
					if (wp_mkdir_p($upload_dir['path'])) {
						$file = $upload_dir['path'].'/'.$grocery_shopping_store_filename;
					} else {
						$file = $upload_dir['basedir'].'/'.$grocery_shopping_store_filename;
					}

					// Create the image  file on the server
					if ( ! function_exists( 'WP_Filesystem' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
					}
					
					WP_Filesystem();
					global $wp_filesystem;
					
					if ( ! $wp_filesystem->put_contents( $file, $grocery_shopping_store_image_data, FS_CHMOD_FILE ) ) {
						wp_die( 'Error saving file!' );
					}

					// Check image file type
					$wp_filetype = wp_check_filetype($grocery_shopping_store_filename, null);

					// Set attachment data
					$grocery_shopping_store_attachment = array(
						'post_mime_type' => $wp_filetype['type'],
						'post_title'     => sanitize_file_name($grocery_shopping_store_filename),
						'post_type'      => 'product',
						'post_status'    => 'inherit',
					);

					// Create the attachment
					$grocery_shopping_store_attach_id = wp_insert_attachment($grocery_shopping_store_attachment, $file, $post_id);

					// Include image.php
					require_once (ABSPATH.'wp-admin/includes/image.php');

					// Define attachment metadata
					$grocery_shopping_store_attach_data = wp_generate_attachment_metadata($grocery_shopping_store_attach_id, $file);

					// Assign metadata to attachment
					wp_update_attachment_metadata($grocery_shopping_store_attach_id, $grocery_shopping_store_attach_data);

					// And finally assign featured image to post
					set_post_thumbnail($post_id, $grocery_shopping_store_attach_id);
				}
				// Create product END
				++$grocery_shopping_store_i;
			}

        
			}

        $this->grocery_shopping_store_customizer_nav_menu();

	    grocery_shopping_store_set_setup_step_done( 'demo' );
		wp_send_json_success( grocery_shopping_store_get_setup_progress() );
	}
}