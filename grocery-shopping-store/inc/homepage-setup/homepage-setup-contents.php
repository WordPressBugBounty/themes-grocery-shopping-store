<?php
/**
 * Wizard
 *
 * @package Grocery_Shopping_Store_Whizzie
 * @author Catapult Themes
 * @since 1.0.0
 */

class Grocery_Shopping_Store_Whizzie {
	
	protected $version = '1.1.0';
	
	/** @var string Current theme name, used as namespace in actions. */
	protected $grocery_shopping_store_theme_name = '';
	protected $grocery_shopping_store_theme_title = '';
	
	/** @var string Wizard page slug and title. */
	protected $grocery_shopping_store_page_slug = '';
	protected $grocery_shopping_store_page_title = '';
	
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
	 * @param $config	Our config parameters
	 */
	public function __construct( $config ) {
		$this->set_vars( $config );
		$this->init();
	}
	
	/**
	 * Set some settings
	 * @since 1.0.0
	 * @param $config	Our config parameters
	 */
	public function set_vars( $config ) {
	
		require_once trailingslashit( WHIZZIE_DIR ) . 'tgm/class-tgm-plugin-activation.php';
		require_once trailingslashit( WHIZZIE_DIR ) . 'tgm/tgm.php';

		if( isset( $config['grocery_shopping_store_page_slug'] ) ) {
			$this->grocery_shopping_store_page_slug = esc_attr( $config['grocery_shopping_store_page_slug'] );
		}
		if( isset( $config['grocery_shopping_store_page_title'] ) ) {
			$this->grocery_shopping_store_page_title = esc_attr( $config['grocery_shopping_store_page_title'] );
		}
		if( isset( $config['steps'] ) ) {
			$this->config_steps = $config['steps'];
		}
		
		$this->grocery_shopping_store_plugin_path = trailingslashit( dirname( __FILE__ ) );
		$relative_url = str_replace( get_template_directory(), '', $this->grocery_shopping_store_plugin_path );
		$this->grocery_shopping_store_plugin_url = trailingslashit( get_template_directory_uri() . $relative_url );
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
		
	}
	
	public function enqueue_scripts() {
		wp_enqueue_style( 'grocery-shopping-store-homepage-setup-style', get_template_directory_uri() . '/inc/homepage-setup/assets/css/homepage-setup-style.css');
		wp_register_script( 'grocery-shopping-store-homepage-setup-script', get_template_directory_uri() . '/inc/homepage-setup/assets/js/homepage-setup-script.js', array( 'jquery' ), time() );
		wp_localize_script( 
			'grocery-shopping-store-homepage-setup-script',
			'whizzie_params',
			array(
				'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
				'wpnonce' 		=> wp_create_nonce( 'whizzie_nonce' ),
				'verify_text'	=> esc_html( 'verifying', 'grocery-shopping-store' )
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
	
	public function tgmpa_load( $status ) {
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
	 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	 */
	public function set_tgmpa_url() {
		$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
		$this->tgmpa_menu_slug = apply_filters( $this->grocery_shopping_store_theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );
		$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && $this->tgmpa_instance->parent_slug !== 'themes.php' ) ? 'admin.php' : 'themes.php';
		$this->tgmpa_url = apply_filters( $this->grocery_shopping_store_theme_name . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );
	}
	
	/**
	 * Make a modal screen for the wizard
	 */
	public function menu_page() {
		add_theme_page( esc_html( $this->grocery_shopping_store_page_title ), esc_html( $this->grocery_shopping_store_page_title ), 'manage_options', $this->grocery_shopping_store_page_slug, array( $this, 'wizard_page' ) );
	}
	
	/**
	 * Make an interface for the wizard
	 */
	public function wizard_page() { 
		tgmpa_load_bulk_installer();

		if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS['tgmpa'] ) ) {
			die( esc_html__( 'Failed to find TGM', 'grocery-shopping-store' ) );
		}

		$url = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'whizzie-setup' );
		$method = '';
		$fields = array_keys( $_POST );

		if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields ) ) ) {
			return true;
		}

		if ( ! WP_Filesystem( $creds ) ) {
			request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );
			return true;
		}

		$grocery_shopping_store_theme = wp_get_theme();
		$grocery_shopping_store_theme_title = $grocery_shopping_store_theme->get( 'Name' );
		$grocery_shopping_store_theme_version = $grocery_shopping_store_theme->get( 'Version' );

		?>
		<div class="wrap">
			<?php
				printf( '<h1>%s %s</h1>', esc_html( $grocery_shopping_store_theme_title ), esc_html( '(Version :- ' . $grocery_shopping_store_theme_version . ')' ) );
			?>
			<div class="homepage-setup">
				<div class="homepage-setup-theme-bundle">
					<div class="homepage-setup-theme-bundle-one">
						<h1><?php echo esc_html__( 'WP Theme Bundle', 'grocery-shopping-store' ); ?></h1>
						<p><?php echo wp_kses_post( 'Get <span>15% OFF</span> on all WordPress themes! Use code <span>"BNDL15OFF"</span> at checkout. Limited time offer!' ); ?></p>
					</div>
					<div class="homepage-setup-theme-bundle-two">
						<p><?php echo wp_kses_post( 'Extra <span>15%</span> OFF' ); ?></p>
					</div>
					<div class="homepage-setup-theme-bundle-three">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/inc/homepage-setup/assets/homepage-setup-images/bundle-banner.png' ); ?>" alt="<?php echo esc_attr__( 'Theme Bundle Image', 'grocery-shopping-store' ); ?>">
					</div>
					<div class="homepage-setup-theme-bundle-four">
						<p><?php echo wp_kses_post( '<span>$2795</span>$69' ); ?></p>
						<a target="_blank" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUNDLE_BUTTON ); ?>"><?php echo esc_html__( 'SHOP NOW', 'grocery-shopping-store' ); ?> <span class="dashicons dashicons-arrow-right-alt2"></span></a>
					</div>
				</div>
			</div>
			
			<div class="card whizzie-wrap">
				<div class="demo_content_image">
					<div class="demo_content">
						<?php
							$grocery_shopping_store_steps = $this->get_steps();
							echo '<ul class="whizzie-menu">';
							foreach ( $grocery_shopping_store_steps as $grocery_shopping_store_step ) {
								$class = 'step step-' . esc_attr( $grocery_shopping_store_step['id'] );
								echo '<li data-step="' . esc_attr( $grocery_shopping_store_step['id'] ) . '" class="' . esc_attr( $class ) . '">';
								printf( '<h2>%s</h2>', esc_html( $grocery_shopping_store_step['title'] ) );

								$content = call_user_func( array( $this, $grocery_shopping_store_step['view'] ) );
								if ( isset( $content['summary'] ) ) {
									printf(
										'<div class="summary">%s</div>',
										wp_kses_post( $content['summary'] )
									);
								}
								if ( isset( $content['detail'] ) ) {
									printf(
										'<div class="detail">%s</div>',
										wp_kses_post( $content['detail'] )
									);
								}
								if ( isset( $grocery_shopping_store_step['button_text'] ) && $grocery_shopping_store_step['button_text'] ) {
									printf( 
										'<div class="button-wrap"><a href="#" class="button button-primary do-it" data-callback="%s" data-step="%s">%s</a></div>',
										esc_attr( $grocery_shopping_store_step['callback'] ),
										esc_attr( $grocery_shopping_store_step['id'] ),
										esc_html( $grocery_shopping_store_step['button_text'] )
									);
								}
								echo '</li>';
							}
							echo '</ul>';
						?>
						
						<ul class="whizzie-nav">
							<?php
							$step_number = 1;	
							foreach ( $grocery_shopping_store_steps as $grocery_shopping_store_step ) {
								echo '<li class="nav-step-' . esc_attr( $grocery_shopping_store_step['id'] ) . '">';
								echo '<span class="step-number">' . esc_html( $step_number ) . '</span>';
								echo '</li>';
								$step_number++;
							}
							?>
							<div class="blank-border"></div>
						</ul>

						<div class="homepage-setup-links">
							<div class="homepage-setup-links buttons">
								<a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_LITE_DOCS_PRO ); ?>" target="_blank" class="button button-primary"><?php echo esc_html__( 'Free Documentation', 'grocery-shopping-store' ); ?></a>
								<a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUY_NOW ); ?>" class="button button-primary" target="_blank"><?php echo esc_html__( 'Get Premium', 'grocery-shopping-store' ); ?></a>
								<a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_DEMO_PRO ); ?>" class="button button-primary" target="_blank"><?php echo esc_html__( 'Premium Demo', 'grocery-shopping-store' ); ?></a>
								<a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_SUPPORT_FREE ); ?>" target="_blank" class="button button-primary"><?php echo esc_html__( 'Support Forum', 'grocery-shopping-store' ); ?></a>
							</div>
						</div> <!-- .demo_image -->

						<div class="step-loading"><span class="spinner"></span></div>
					</div> <!-- .demo_content -->

					<div class="homepage-setup-image">
						<div class="homepage-setup-theme-buynow">
							<div class="homepage-setup-theme-buynow-one">
								<h1><?php echo wp_kses_post( 'Grocery Store<br>WordPress Theme' ); ?></h1>
								<p><?php echo wp_kses_post( '<span>25%<br>Off</span> SHOP NOW' ); ?></p>
							</div>
							<div class="homepage-setup-theme-buynow-two">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/inc/homepage-setup/assets/homepage-setup-images/grocery-shopping-store.png' ); ?>" alt="<?php echo esc_attr__( 'Theme Bundle Image', 'grocery-shopping-store' ); ?>">
							</div>
							<div class="homepage-setup-theme-buynow-three">
								<p><?php echo wp_kses_post( 'Get <span>25% OFF</span> on Premium Grocery Store WordPress Theme Use code <span>"NYTHEMES25"</span> at checkout.' ); ?></p>
							</div>
							<div class="homepage-setup-theme-buynow-four">
								<a target="_blank" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUY_NOW ); ?>"><?php echo esc_html__( 'Upgrade To Pro With Just $40', 'grocery-shopping-store' ); ?></a>
							</div>
						</div>
					</div> <!-- .demo_image -->

				</div> <!-- .demo_content_image -->
			</div> <!-- .whizzie-wrap -->
		</div> <!-- .wrap -->
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
				'id'			=> 'plugins',
				'title'			=> __( 'Install and Activate Essential Plugins', 'grocery-shopping-store' ),
				'icon'			=> 'admin-plugins',
				'view'			=> 'get_step_plugins',
				'callback'		=> 'install_plugins',
				'button_text'	=> __( 'Install Plugins', 'grocery-shopping-store' ),
				'can_skip'		=> true
			),
			'widgets' => array(
				'id'			=> 'widgets',
				'title'			=> __( 'Setup Home Page', 'grocery-shopping-store' ),
				'icon'			=> 'welcome-widgets-menus',
				'view'			=> 'get_step_widgets',
				'callback'		=> 'grocery_shopping_store_install_widgets',
				'button_text'	=> __( 'Start Home Page Setup', 'grocery-shopping-store' ),
				'can_skip'		=> false
			),
			'done' => array(
				'id'			=> 'done',
				'title'			=> __( 'Customize Your Site', 'grocery-shopping-store' ),
				'icon'			=> 'yes',
				'view'			=> 'get_step_done',
				'callback'		=> ''
			)
		);
		
		// Iterate through each step and replace with dev config values
		if( $grocery_shopping_store_dev_steps ) {
			// Configurable elements - these are the only ones the dev can update from homepage-setup-settings.php
			$can_config = array( 'title', 'icon', 'button_text', 'can_skip' );
			foreach( $grocery_shopping_store_dev_steps as $grocery_shopping_store_dev_step ) {
				// We can only proceed if an ID exists and matches one of our IDs
				if( isset( $grocery_shopping_store_dev_step['id'] ) ) {
					$id = $grocery_shopping_store_dev_step['id'];
					if( isset( $grocery_shopping_store_steps[$id] ) ) {
						foreach( $can_config as $element ) {
							if( isset( $grocery_shopping_store_dev_step[$element] ) ) {
								$grocery_shopping_store_steps[$id][$element] = $grocery_shopping_store_dev_step[$element];
							}
						}
					}
				}
			}
		}
		return $grocery_shopping_store_steps;
	}

	/**
	 * Get the content for the plugins step
	 * @return $content Array
	 */
	public function get_step_plugins() {
		$plugins = $this->get_plugins();
		$content = array(); 
		
		// Add plugin name and type at the top
		$content['detail'] = '<div class="plugin-info">';
		$content['detail'] .= '<p><strong>Plugin</strong></p>';
		$content['detail'] .= '<p><strong>Type</strong></p>';
		$content['detail'] .= '</div>';
		
		// The detail element is initially hidden from the user
		$content['detail'] .= '<ul class="whizzie-do-plugins">';
		
		// Add each plugin into a list
		foreach( $plugins['all'] as $slug=>$plugin ) {
			if ( $slug != 'yith-woocommerce-wishlist') {
				$content['detail'] .= '<li data-slug="' . esc_attr( $slug ) . '">' . esc_html( $plugin['name'] ) . '<span>';
				$keys = array();
				if ( isset( $plugins['install'][ $slug ] ) ) {
					$keys[] = 'Installation';
				}
				if ( isset( $plugins['update'][ $slug ] ) ) {
					$keys[] = 'Update';
				}
				if ( isset( $plugins['activate'][ $slug ] ) ) {
					$keys[] = 'Activation';
				}
				$content['detail'] .= implode( ' and ', $keys ) . ' required';
				$content['detail'] .= '</span></li>';
			}
		}
		
		$content['detail'] .= '</ul>';
		
		return $content;
	}
	
	/**
	 * Print the content for the widgets step
	 * @since 1.1.0
	 */
	public function get_step_widgets() { ?> <?php }
	
	/**
	 * Print the content for the final step
	 */
	public function get_step_done() { ?>
		<div id="grocery-shopping-store-demo-setup-guid">
			<div class="customize_div">
				<div class="customize_div finish">
					<div class="customize_div finish btns">
						<h3><?php echo esc_html( 'Your Site Is Ready To View' ); ?></h3>
						<div class="btnsss">
							<a target="_blank" href="<?php echo esc_url( get_home_url() ); ?>" class="button button-primary">
								<?php esc_html_e( 'View Your Site', 'grocery-shopping-store' ); ?>
							</a>
							<a target="_blank" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary">
								<?php esc_html_e( 'Customize Your Site', 'grocery-shopping-store' ); ?>
							</a>
							<a href="<?php echo esc_url(admin_url()); ?>" class="button button-primary">
								<?php esc_html_e( 'Finsh', 'grocery-shopping-store' ); ?>
							</a>
						</div>
					</div>
					<div class="grocery-shopping-store-setup-finish">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>"/>
					</div>
				</div>
			</div>
		</div>
	<?php }

	/**
	 * Get the plugins registered with TGMPA
	 */
	public function get_plugins() {
		$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$plugins = array(
			'all' 		=> array(),
			'install'	=> array(),
			'update'	=> array(),
			'activate'	=> array()
		);
		foreach( $instance->plugins as $slug=>$plugin ) {
			if( $instance->is_plugin_active( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
				// Plugin is installed and up to date
				continue;
			} else {
				$plugins['all'][$slug] = $plugin;
				if( ! $instance->is_plugin_installed( $slug ) ) {
					$plugins['install'][$slug] = $plugin;
				} else {
					if( false !== $instance->does_plugin_have_update( $slug ) ) {
						$plugins['update'][$slug] = $plugin;
					}
					if( $instance->can_plugin_activate( $slug ) ) {
						$plugins['activate'][$slug] = $plugin;
					}
				}
			}
		}
		return $plugins;
	}

	/**
	 * Get the widgets.wie file from the /content folder
	 * @return Mixed	Either the file or false
	 * @since 1.1.0
	 */
	public function has_widget_file() {
		if( file_exists( $this->widget_file_url ) ) {
			return true;
		}
		return false;
	}
	
	public function setup_plugins() {
		if ( ! check_ajax_referer( 'whizzie_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
			wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No Slug Found','grocery-shopping-store' ) ) );
		}
		$json = array();
		// send back some json we use to hit up TGM
		$plugins = $this->get_plugins();
		
		// what are we doing with this plugin?
		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( 'Activating Plugin','grocery-shopping-store' ),
				);
				break;
			}
		}
		foreach ( $plugins['update'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html__( 'Updating Plugin','grocery-shopping-store' ),
				);
				break;
			}
		}
		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( 'Installing Plugin','grocery-shopping-store' ),
				);
				break;
			}
		}
		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
			wp_send_json( $json );
		} else {
			wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Success','grocery-shopping-store' ) ) );
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
				$grocery_shopping_store_locations = get_theme_mod('nav_menu_locations');
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
				$grocery_shopping_store_attach_id = wp_insert_attachment( $grocery_shopping_store_attachment, $file, $post_id );

				// Include image.php
				require_once(ABSPATH . 'wp-admin/includes/image.php');

				// Define attachment metadata
				$grocery_shopping_store_attach_data = wp_generate_attachment_metadata( $grocery_shopping_store_attach_id, $file );

				// Assign metadata to attachment
				wp_update_attachment_metadata( $grocery_shopping_store_attach_id, $grocery_shopping_store_attach_data );

				update_woocommerce_term_meta( $grocery_shopping_store_parent_category['term_id'], 'thumbnail_id', $grocery_shopping_store_attach_id );

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

        
        $this->grocery_shopping_store_customizer_nav_menu();

	    exit;
	}
}