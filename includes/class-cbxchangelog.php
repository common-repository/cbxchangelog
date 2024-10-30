<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/includes
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXChangelog {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.1.1
	 */
	private static $instance = null;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = CBXCHANGELOG_PLUGIN_NAME;
		$this->version     = CBXCHANGELOG_PLUGIN_VERSION;


		$this->load_dependencies();


		$this->define_common_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}//end of constructor

	/**
	 * Singleton Instance.
	 *
	 * Ensures only one instance of cbxchangelog is loaded or can be loaded.
	 *
	 * @return self Main instance.
	 * @see run_cbxchangelog()
	 * @since  1.1.1
	 * @static
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}//end method instance


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - CBXChangelog_Admin. Defines all hooks for the admin area.
	 * - CBXChangelog_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cbxchangelog-tpl-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxchangelog-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxchangelog-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cbxchangelog-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cbxchangelog-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/classic-widgets/cbxchangelog-widget/cbxchangelog-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cbxchangelog-functions.php';
	}//end method load_dependencies

	/**
	 * All the common hooks
	 *
	 * @since    1.1.1
	 * @access   private
	 */
	private function define_common_hooks() {
		add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ] );
	}//end method define_common_hooks

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.1.1
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'cbxchangelog', false, CBXCHANGELOG_ROOT_PATH . 'languages/' );
	}//end method load_plugin_textdomain

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		global $wp_version;

		$plugin_admin = new CBXChangelog_Admin( $this->get_plugin_name(), $this->get_version() );


		//adding the setting action
		add_action( 'admin_init', [ $plugin_admin, 'setting_init' ] );

		//add new post type
		add_action( 'init', [ $plugin_admin, 'init_post_types' ], 0 );

		//create opverview menu page
		add_action( 'admin_menu', [ $plugin_admin, 'admin_pages' ], 11 );


		add_action( 'add_meta_boxes', [ $plugin_admin, 'add_meta_boxes_form' ] );

		//meta save
		add_action( 'save_post', [ $plugin_admin, 'metabox_save' ], 10, 3 ); //save meta

		add_filter( 'manage_edit-cbxchangelog_columns', [ $plugin_admin, 'cbxchangelog_add_new_columns' ] );
		add_action( 'manage_cbxchangelog_posts_custom_column', [ $plugin_admin, 'cbxchangelog_manage_columns' ] );


		add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueue_scripts' ] );

		// Plugin Support
		add_filter( 'plugin_action_links_' . CBXCHANGELOG_BASE_NAME, [ $plugin_admin, 'plugin_action_links' ] );
		add_filter( 'plugin_row_meta', [ $plugin_admin, 'plugin_row_meta' ], 10, 4 );

		add_action( 'admin_head', [ $plugin_admin, 'remove_date_filter' ] );

		//upgrade process
		add_action( 'upgrader_process_complete', [ $plugin_admin, 'plugin_upgrader_process_complete' ], 10, 2 );
		add_action( 'admin_notices', [ $plugin_admin, 'plugin_activate_upgrade_notices' ] );

		//update manager
		add_filter( 'pre_set_site_transient_update_plugins', [ $plugin_admin, 'pre_set_site_transient_update_plugins_pro_addon' ] );
		add_action( 'in_plugin_update_message-' . 'cbxchangelogpro/cbxchangelogpro.php', [ $plugin_admin, 'plugin_update_message_pro_addon' ] );

		//gutenberg blocks
		if ( version_compare( $wp_version, '5.8' ) >= 0 ) {
			add_filter( 'block_categories_all', [ $plugin_admin, 'gutenberg_block_categories' ], 10, 2 );
		} else {
			add_filter( 'block_categories', [ $plugin_admin, 'gutenberg_block_categories' ], 10, 2 );
		}

		add_action( 'init', [ $plugin_admin, 'gutenberg_blocks' ] );
		//add_action('enqueue_block_editor_assets', [$plugin_admin, 'enqueue_block_editor_assets']);//Hook: Editor assets.

		//ajax plugin reset
		add_action( 'wp_ajax_cbxchangelog_settings_reset_load', [ $plugin_admin, 'settings_reset_load' ] );
		add_action( 'wp_ajax_cbxchangelog_settings_reset', [ $plugin_admin, 'plugin_reset' ] );
	}//end method define_admin_hooks

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		global $wp_version;

		$plugin_public = new CBXChangelog_Public( $this->get_plugin_name(), $this->get_version() );

		add_action( 'init', [ $plugin_public, 'init_shortcodes' ] );
		add_filter( 'the_content', [ $plugin_public, 'append_cbxchangelog' ] );       //append changelog to changelog type post type in frontend.


		add_action( 'wp_enqueue_scripts', [ $plugin_public, 'enqueue_styles' ] );
		//at this moment we don't need any js file for this plugin
		//add_action( 'wp_enqueue_scripts', [$plugin_public, 'enqueue_scripts'] );

		//classic widgets
		add_action( 'widgets_init', [ $plugin_public, 'init_widgets' ] );

		//elementor
		add_action( 'elementor/widgets/widgets_registered', [ $plugin_public, 'init_elementor_widgets' ] );


		add_action( 'elementor/elements/categories_registered', [ $plugin_public, 'add_elementor_widget_categories' ] );

		add_action( 'elementor/editor/before_enqueue_scripts', [ $plugin_public, 'elementor_icon_loader' ], 99999 );

		//Wpbakery
		add_action( 'vc_before_init', [ $plugin_public, 'vc_before_init_actions' ] ); //priority set to 12 from default 10

	}//end define_public_hooks

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}//end method get_plugin_name

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}//end method get_version
}//end method CBXChangelog