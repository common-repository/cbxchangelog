<?php

//use \Michelf\Markdown;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/admin
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXChangelog_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;


	private $settings_api;
	protected $plugin_basename;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param  string  $plugin_name  The name of this plugin.
	 * @param  string  $version  The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$this->version = current_time( 'timestamp' ); //for development time only
		}

		$this->plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $plugin_name . '.php' );

		//get instance of setting api
		$this->settings_api = new CBXChangelogSettings();
	}//end of constructor

	/**
	 * settings
	 */
	public function setting_init() {
		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings_api->admin_init();
	}//end setting_init

	/**
	 * Global Setting Sections
	 *
	 * @return null
	 */
	public function get_settings_sections() {
		return CBXChangelogHelper::get_settings_sections();
	}//end get_settings_sections

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	public function get_settings_fields() {
		return CBXChangelogHelper::get_settings_fields();
	}//end get_settings_fields

	/**
	 * Register Custom Post Type cbxform
	 *
	 * @since    3.7.0
	 */
	public function init_post_types() {
		//$setting = $this->settings_api;

		$post_type = 'cbxchangelog';
		$post_slug = 'cbxchangelog';

		$labels = [
			'name'               => _x( 'Changelog', 'Post Type General Name', 'cbxchangelog' ),
			'singular_name'      => _x( 'Changelog', 'Post Type Singular Name', 'cbxchangelog' ),
			'menu_name'          => __( 'Changelogs', 'cbxchangelog' ),
			'parent_item_colon'  => __( 'Parent Item:', 'cbxchangelog' ),
			'all_items'          => __( 'Changelogs', 'cbxchangelog' ),
			'view_item'          => __( 'View Changelog', 'cbxchangelog' ),
			'add_new_item'       => __( 'Add New Changelog', 'cbxchangelog' ),
			'add_new'            => __( 'Add New', 'cbxchangelog' ),
			'edit_item'          => __( 'Edit Changelog', 'cbxchangelog' ),
			'update_item'        => __( 'Update Changelog', 'cbxchangelog' ),
			'search_items'       => __( 'Search Changelog', 'cbxchangelog' ),
			'not_found'          => __( 'Not found', 'cbxchangelog' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'cbxchangelog' ),
		];

		$args   = [
			'label'               => __( 'Changelog', 'cbxchangelog' ),
			'description'         => __( 'Changelog', 'cbxchangelog' ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'editor', 'thumbnail', 'author', 'excerpt', 'comments' ],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'rewrite'             => [ 'slug' => apply_filters( 'cbxchangelog_single_slug', $post_slug ) ],
			'menu_icon'           => plugins_url( 'assets/images/cbxchangelog_menu_icon.png', dirname( __FILE__ ) ),
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		];
		register_post_type( $post_type, $args );
	}//end init_post_types


	/**
	 * Custom admin menu pages
	 */
	public function admin_pages() {
		$page = isset( $_GET['page'] ) ? esc_attr( wp_unslash( $_GET['page'] ) ) : '';



		$settings_page_hook = add_submenu_page( 'edit.php?post_type=cbxchangelog', esc_html__( 'CBX Changelog Settings', 'cbxchangelog' ), esc_html__( 'Setting', 'cbxchangelog' ), 'manage_options', 'cbxchangelog-settings', [
			$this,
			'menu_settings'
		] );

		$support_page_hook = add_submenu_page( 'edit.php?post_type=cbxchangelog', esc_html__( 'Helps & Updates', 'cbxchangelog' ), esc_html__( 'Helps & Updates', 'cbxchangelog' ), 'manage_options', 'cbxchangelog-support', [
			$this,
			'menu_support'
		] );
	}//end admin_overview_menu_page

	/**
	 * Show Setting page
	 */
	public function menu_settings() {
		echo cbxchangelog_get_template_html( 'admin/settings.php', [
			'admin_ref' => $this,
			'settings'  => $this->settings_api
		] );
	}//end menu_settings

	/**
	 * Render the help & support page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function menu_support() {
		echo cbxchangelog_get_template_html( 'admin/support.php' );
	}//end method menu_support

	/**
	 * Add metabox for change logs and shortcode copy
	 *
	 * @since    1.0.0
	 */
	public function add_meta_boxes_form() {
		$allowed_post_types = CBXChangelogHelper::supported_post_types();

		foreach ( $allowed_post_types as $post_type ) {
			//add meta box for creating form and form elements
			add_meta_box( 'cbxchangelog_metabox', esc_html__( 'Change Logs', 'cbxchangelog' ), [ $this, 'changelog_metabox_display' ], $post_type, 'normal', 'high' );
			add_meta_box( 'cbxchangelog_shortcode', esc_html__( 'Shortcode', 'cbxchangelog' ), [ $this, 'shortcode_metabox_display' ], $post_type, 'side', 'low' );
		}
	}//end add_meta_boxes_form

	/**
	 * Render change log metabox
	 *
	 * @param $post
	 *
	 * since v1.0.0
	 */
	public function changelog_metabox_display( $post ) {
		if ( isset( $post->ID ) && $post->ID > 0 ) {
			$post_id   = intval( $post->ID );
			$post_type = $post->post_type;

			wp_nonce_field( 'cbxchangelog_' . $post_type . '_meta_box', 'cbxchangelog_' . $post_type . '_meta_box_nonce' );

			echo cbxchangelog_get_template_html( 'admin/metabox_changelogs.php',
				[
					'admin_ref' => $this,
					'settings'  => $this->settings_api,
					'post_id'   => $post_id,
					'post_type' => $post_type,
					'post'      => $post,
				]
			);
		}
	}//end changelog_metabox_display

	/**
	 * Render shortcode meta box
	 *
	 * @param $post
	 */
	public function shortcode_metabox_display( $post ) {
		if ( isset( $post->ID ) && $post->ID > 0 ) {
			$post_id   = intval( $post->ID );
			$post_type = $post->post_type;

			echo cbxchangelog_get_template_html( 'admin/metabox_shortcode.php',
				[
					'admin_ref' => $this,
					'settings'  => $this->settings_api,
					'post_id'   => $post_id,
					'post_type' => $post_type,
				]
			);
		}
	}//end cbxchangelog_shortcode_display


	/**
	 * Save meta box for cbxchangelog
	 *
	 * @param $post_id
	 * @param $post
	 * @param $update
	 *
	 * @return mixed|void
	 */
	public function metabox_save( $post_id, $post, $update ) {
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}


		$post_type = $post->post_type;

		// Check if our nonce is set.
		if ( ! isset( $_POST[ 'cbxchangelog_' . $post_type . '_meta_box_nonce' ] ) ) {
			return;
		}


		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST[ 'cbxchangelog_' . $post_type . '_meta_box_nonce' ], 'cbxchangelog_' . $post_type . '_meta_box' ) ) {
			return;
		}


		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && $post_type == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}


		$allowed_post_types = CBXChangelogHelper::supported_post_types();


		if ( ! in_array( $post_type, $allowed_post_types ) ) {
			return;
		}


		//now we are free to save the meta

		if ( isset( $_POST['cbxchangelog_logs'] ) && is_array( $_POST['cbxchangelog_logs'] ) && sizeof( $_POST['cbxchangelog_logs'] ) > 0 ) {


			$submitted_values = $_POST['cbxchangelog_logs'];

			$valid_values = [];

			foreach ( $submitted_values as $value ) {
				$valid_value = [];

				$version = isset( $value['version'] ) ? sanitize_text_field( $value['version'] ) : '';
				$url     = isset( $value['url'] ) ? esc_url_raw( $value['url'] ) : '';
				$date    = isset( $value['date'] ) ? sanitize_text_field( $value['date'] ) : '';
				$note    = isset( $value['note'] ) ? sanitize_textarea_field( $value['note'] ) : '';

				$valid_value['version'] = $version;
				$valid_value['url']     = $url;
				$valid_value['date']    = $date;
				$valid_value['note']    = $note;

				if ( isset( $value['feature'] ) && is_array( $value['feature'] ) ) {
					$value['feature'] = array_filter( $value['feature'] );
					if ( sizeof( $value['feature'] ) > 0 ) {
						foreach ( $value['feature'] as $single_feature ) {

							$single_feature = sanitize_text_field( $single_feature );

							if ( $single_feature == '' ) {
								continue;
							}//let's not store any empty feature

							$valid_value['feature'][] = $single_feature;
						}
					}
				} else {
					$value['feature'] = [];
				}

				if ( isset( $value['label'] ) && is_array( $value['label'] ) ) {
					$value['label'] = array_filter( $value['label'] );
					if ( sizeof( $value['label'] ) > 0 ) {
						foreach ( $value['label'] as $label ) {
							$label = sanitize_text_field( $label );
							if ( $label == '' ) {
								continue;
							} //lets not store any empty feature
							$valid_value['label'][] = $label;
						}
					}
				} else {
					$value['label'] = [];
				}


				$valid_values[] = $valid_value;
			}


			//now update post meta
			update_post_meta( $post_id, '_cbxchangelog', $valid_values );

		} else {
			//no values submitted, so delete the post meta to keep the database free from junk
			delete_post_meta( $post_id, '_cbxchangelog' );
		}

		if ( isset( $_POST['cbxchangelog_extra'] ) && is_array( $_POST['cbxchangelog_extra'] ) && sizeof( $_POST['cbxchangelog_extra'] ) > 0 ) {
			$extras = $_POST['cbxchangelog_extra'];

			$extras['show_url']      = isset( $extras['show_url'] ) ? intval( $extras['show_url'] ) : 1;
			$extras['show_label']    = isset( $extras['show_label'] ) ? intval( $extras['show_label'] ) : 1;
			$extras['show_date']     = isset( $extras['show_date'] ) ? intval( $extras['show_date'] ) : 1;
			$extras['relative_date'] = isset( $extras['relative_date'] ) ? intval( $extras['relative_date'] ) : 0;
			$extras['layout']        = isset( $extras['layout'] ) ? esc_attr( wp_unslash( $extras['layout'] ) ) : 'prepros';
			$extras['order']         = isset( $extras['order'] ) ? esc_attr( wp_unslash( $extras['order'] ) ) : 'desc';
			$extras['orderby']       = isset( $extras['orderby'] ) ? esc_attr( wp_unslash( $extras['orderby'] ) ) : 'order';

			//now update post meta
			update_post_meta( $post_id, '_cbxchangelog_extra', $extras );

		} else {
			//no values submitted, so delete the post meta to keep the database free from junk
			delete_post_meta( $post_id, '_cbxchangelog_extra' );
		}


		do_action( 'cbxchangelog_meta_save', $post_id, $post, $update );

	}//end metabox_save

	/**
	 * Add or adjust col for cbxchangelog post type
	 *
	 * @param $cbxchangelog_columns
	 *
	 * @return array
	 *
	 */
	public function cbxchangelog_add_new_columns( $cbxchangelog_columns ) {
		if ( isset( $cbxchangelog_columns['date'] ) ) {
			unset( $cbxchangelog_columns['date'] );
		}
		if ( isset( $cbxchangelog_columns['author'] ) ) {
			unset( $cbxchangelog_columns['author'] );
		}
		if ( isset( $cbxchangelog_columns['comments'] ) ) {
			unset( $cbxchangelog_columns['comments'] );
		}
		$cbxchangelog_columns['shortcode'] = esc_html__( 'Shortcode', 'cbxchangelog' );

		return $cbxchangelog_columns;
	}//end cbxchangelog_add_new_columns

	/**
	 * Add extra cols information for cbxchangelog post type
	 *
	 * @param $column_name
	 */
	public function cbxchangelog_manage_columns( $column_name ) {
		global $wpdb, $post;
		$post_id   = intval( $post->ID );
		$post_type = $post->post_type;


		switch ( $column_name ) {
			case 'shortcode':
				echo '<span id="cbxchangelogshortcode-' . $post_id . '" class="cbxchangelogshortcode cbxchangelogshortcode-' . $post_id . '">[cbxchangelog id="' . $post_id . '"]</span>';
				echo '<span class="cbxchangelogshortcodetrigger" data-clipboard-text=\'[cbxchangelog id="' . $post_id . '"]\' title="' . esc_html__( "Click to copy", 'cbxchangelog' ) . '">                        
                     </span>';
				break;
			default:
				break;
		}// end switch
	}//end cbxchangelog_manage_columns


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		$version = $this->version;
		$page    = isset( $_GET['page'] ) ? esc_attr( wp_unslash( $_GET['page'] ) ) : '';
		//$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';


		$css_url_part     = CBXCHANGELOG_ROOT_URL . 'assets/css/';
		$js_url_part      = CBXCHANGELOG_ROOT_URL . 'assets/js/';
		$vendors_url_part = CBXCHANGELOG_ROOT_URL . 'assets/vendors/';

		$css_path_part     = CBXCHANGELOG_ROOT_PATH . 'assets/css/';
		$js_path_part      = CBXCHANGELOG_ROOT_PATH . 'assets/js/';
		$vendors_path_part = CBXCHANGELOG_ROOT_PATH . 'assets/vendors/';

		global $post_type;

		$allowed_post_types = CBXChangelogHelper::supported_post_types();


		//only for post add and edit screens
		if ( $post_type == 'cbxchangelog' && $hook == 'edit.php' && $page == '' ) {
			wp_register_style( 'cbxchangelog-listing', $css_url_part . 'cbxchangelog-listing.css', [], $version, 'all' );
			wp_enqueue_style( 'cbxchangelog-listing' );
		} elseif ( in_array( $post_type, $allowed_post_types ) && ( $hook == 'post.php' || $hook == 'post-new.php' ) && $page == '' ) {
			wp_register_style( 'awesome-notifications', $vendors_url_part . 'awesome-notifications/style.css', [], $version );
			wp_register_style( 'jquery-ui', $vendors_url_part . 'ui-lightness/jquery-ui.min.css', [], $version );

			$listing_css_dep = [ 'jquery-ui', 'awesome-notifications' ];

			wp_register_style( 'cbxchangelog-listing', $css_url_part . 'cbxchangelog-listing.css', $listing_css_dep, $version, 'all' );

			wp_enqueue_style( 'jquery-ui' );
			wp_enqueue_style( 'awesome-notifications' );
			wp_enqueue_style( 'cbxchangelog-listing' );
		}

		//only for setting pages
		if ( $page == 'cbxchangelog-settings' ) {
			wp_register_style( 'awesome-notifications', $vendors_url_part . 'awesome-notifications/style.css', [], $version );
			wp_register_style( 'pickr', $vendors_url_part . 'pickr/themes/classic.min.css', [], $version );
			wp_register_style( 'select2', $vendors_url_part . 'select2/css/select2.min.css', [], $version );

			wp_register_style( 'cbxchangelog-admin', $css_url_part . 'cbxchangelog-admin.css', [], $version );

			$setting_css_deps = [ 'select2', 'awesome-notifications', 'pickr', 'cbxchangelog-admin' ];

			wp_register_style( 'cbxchangelog-setting', $css_url_part . 'cbxchangelog-setting.css', $setting_css_deps, $version, 'all' );


			wp_enqueue_style( 'select2' );
			wp_enqueue_style( 'awesome-notifications' );
			wp_enqueue_style( 'pickr' );
			wp_enqueue_style( 'cbxchangelog-admin' );//common admin styles
			wp_enqueue_style( 'cbxchangelog-setting' );
		}

		if ( $page == 'cbxchangelog-support' ) {
			wp_register_style( 'cbxchangelog-admin', $css_url_part . 'cbxchangelog-admin.css', [], $version );
			wp_enqueue_style( 'cbxchangelog-admin' );//common admin styles
		}
	}//end enqueue_styles

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		global $post;
		global $post_type;

		$version = $this->version;
		$page    = isset( $_GET['page'] ) ? esc_attr( wp_unslash( $_GET['page'] ) ) : '';
		//$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		$css_url_part     = CBXCHANGELOG_ROOT_URL . 'assets/css/';
		$js_url_part      = CBXCHANGELOG_ROOT_URL . 'assets/js/';
		$vendors_url_part = CBXCHANGELOG_ROOT_URL . 'assets/vendors/';

		$css_path_part     = CBXCHANGELOG_ROOT_PATH . 'assets/css/';
		$js_path_part      = CBXCHANGELOG_ROOT_PATH . 'assets/js/';
		$vendors_path_part = CBXCHANGELOG_ROOT_PATH . 'assets/vendors/';


		$allowed_post_types = CBXChangelogHelper::supported_post_types();


		//only for post add/edit screen
		if ( in_array( $post_type, $allowed_post_types ) && ( $hook == 'post.php' || $hook == 'post-new.php' ) ) {
			wp_enqueue_script( 'jquery' );

			wp_register_script( 'pickr', $vendors_url_part . 'pickr/pickr.min.js', [], $version, true );
			wp_register_script( 'awesome-notifications', $vendors_url_part . 'awesome-notifications/script.js', [], $version, true );
			wp_register_script( 'jquery-mustache', $vendors_url_part . 'mustache/jquery.mustache.js', [ 'jquery' ], $version, true );
			wp_register_script( 'mustache', $vendors_url_part . 'mustache/mustache.min.js', [
				'jquery-mustache',
				'jquery'
			], $version, true );

			wp_register_script( 'cbxchangelog-admin', $js_url_part . 'cbxchangelog-admin.js', [
				'jquery',
				'jquery-ui-core',
				'jquery-ui-sortable',
				'jquery-ui-datepicker',

				'jquery-mustache',
				'mustache',

				'pickr',
				'awesome-notifications',
			], $version, true );

			//$changelog_id =  isset($_REQUEST['id'])? absint($_REQUEST['id']) : 0;
			$changelog_id = isset( $post->ID ) ? absint( $post->ID ) : 0;


			/*$import_modal_html = '<div id="changelog_import_modal_wrap" class="cbx-chota">';
			$import_modal_html .= '<h2>' . esc_html__( 'Import Setting: Json file', 'changelog' ) . '</h2>';
			$import_modal_html .= '<form method="post" id="changelog_import_form" data-post-id="' . $changelog_id . '">';
			$import_modal_html .= '<input type="file" name="file" id="changelog_import_file" data-post-id="' . $changelog_id . '" />';
			//$import_modal_html .= '<button class="button primary" type="submit">'.esc_html__('Upload', 'changelog').'</button>';
			$import_modal_html .= '</form>';
			$import_modal_html .= '</div>';*/

			wp_localize_script( 'cbxchangelog-admin', 'cbxchangelog_admin', apply_filters( 'cbxchangelog_admin_localize_script',
					[
						'deleteconfirm'       => esc_html__( 'Are you sure?', 'cbxchangelog' ),
						'deleteconfirm_desc'  => esc_html__( 'Are you sure to delete this item?', 'cbxchangelog' ),
						'deleteconfirmok'     => esc_html__( 'Sure', 'cbxchangelog' ),
						'deleteconfirmcancel' => esc_html__( 'Oh! No', 'cbxchangelog' ),
						'deletelastitem'      => esc_html__( 'This only feature can not be deleted. Either edit or delete the total release.', 'cbxchangelog' ),
						'copycmds'            => [
							'copy'       => esc_html__( 'Copy', 'cbxchangelog' ),
							'copied'     => esc_html__( 'Copied', 'cbxchangelog' ),
							'copy_tip'   => esc_html__( 'Click to copy', 'cbxchangelog' ),
							'copied_tip' => esc_html__( 'Copied to clipboard', 'cbxchangelog' ),
						],
						'pickr_i18n'          => [
							// Strings visible in the UI
							'ui:dialog'       => esc_html__( 'color picker dialog', 'cbxchangelog' ),
							'btn:toggle'      => esc_html__( 'toggle color picker dialog', 'cbxchangelog' ),
							'btn:swatch'      => esc_html__( 'color swatch', 'cbxchangelog' ),
							'btn:last-color'  => esc_html__( 'use previous color', 'cbxchangelog' ),
							'btn:save'        => esc_html__( 'Save', 'cbxchangelog' ),
							'btn:cancel'      => esc_html__( 'Cancel', 'cbxchangelog' ),
							'btn:clear'       => esc_html__( 'Clear', 'cbxchangelog' ),

							// Strings used for aria-labels
							'aria:btn:save'   => esc_html__( 'save and close', 'cbxchangelog' ),
							'aria:btn:cancel' => esc_html__( 'cancel and close', 'cbxchangelog' ),
							'aria:btn:clear'  => esc_html__( 'clear and close', 'cbxchangelog' ),
							'aria:input'      => esc_html__( 'color input field', 'cbxchangelog' ),
							'aria:palette'    => esc_html__( 'color selection area', 'cbxchangelog' ),
							'aria:hue'        => esc_html__( 'hue selection slider', 'cbxchangelog' ),
							'aria:opacity'    => esc_html__( 'selection slider', 'cbxchangelog' ),
						],
						'awn_options'         => [
							'tip'           => esc_html__( 'Tip', 'cbxchangelog' ),
							'info'          => esc_html__( 'Info', 'cbxchangelog' ),
							'success'       => esc_html__( 'Success', 'cbxchangelog' ),
							'warning'       => esc_html__( 'Attention', 'cbxchangelog' ),
							'alert'         => esc_html__( 'Error', 'cbxchangelog' ),
							'async'         => esc_html__( 'Loading', 'cbxchangelog' ),
							'confirm'       => esc_html__( 'Confirmation', 'cbxchangelog' ),
							'confirmOk'     => esc_html__( 'OK', 'cbxchangelog' ),
							'confirmCancel' => esc_html__( 'Cancel', 'cbxchangelog' )
						],
						'validation'          => [
							'required'    => esc_html__( 'This field is required.', 'cbxchangelog' ),
							'remote'      => esc_html__( 'Please fix this field.', 'cbxchangelog' ),
							'email'       => esc_html__( 'Please enter a valid email address.', 'cbxchangelog' ),
							'url'         => esc_html__( 'Please enter a valid URL.', 'cbxchangelog' ),
							'date'        => esc_html__( 'Please enter a valid date.', 'cbxchangelog' ),
							'dateISO'     => esc_html__( 'Please enter a valid date ( ISO ).', 'cbxchangelog' ),
							'number'      => esc_html__( 'Please enter a valid number.', 'cbxchangelog' ),
							'digits'      => esc_html__( 'Please enter only digits.', 'cbxchangelog' ),
							'equalTo'     => esc_html__( 'Please enter the same value again.', 'cbxchangelog' ),
							'maxlength'   => esc_html__( 'Please enter no more than {0} characters.', 'cbxchangelog' ),
							'minlength'   => esc_html__( 'Please enter at least {0} characters.', 'cbxchangelog' ),
							'rangelength' => esc_html__( 'Please enter a value between {0} and {1} characters long.', 'cbxchangelog' ),
							'range'       => esc_html__( 'Please enter a value between {0} and {1}.', 'cbxchangelog' ),
							'max'         => esc_html__( 'Please enter a value less than or equal to {0}.', 'cbxchangelog' ),
							'min'         => esc_html__( 'Please enter a value greater than or equal to {0}.', 'cbxchangelog' ),
							'recaptcha'   => esc_html__( 'Please check the captcha.', 'cbxchangelog' ),
						],
						'lang'                => get_user_locale(),
//					'import_modal'        => $import_modal_html,
//					'import_modal_progress' => '<p>' . esc_html__( 'Please wait, importing', 'cbxchangelog' ) . '</p>',
//					'nonce'               => wp_create_nonce( 'cbxchangelog_nonce' ),
						'ajaxurl'             => admin_url( 'admin-ajax.php' ),
					] )
			);


			wp_enqueue_media();

			wp_enqueue_script( 'jquery' );

			wp_enqueue_style( 'jquery-ui-core' );       //jquery ui core
			wp_enqueue_style( 'jquery-ui-sortable' );   //jquery ui sortable
			wp_enqueue_style( 'jquery-ui-datepicker' ); //jquery ui datepicker

			wp_enqueue_script( 'jquery-mustache' );
			wp_enqueue_script( 'mustache' );

			wp_enqueue_script( 'pickr', );
			wp_enqueue_script( 'awesome-notifications' );

//			wp_enqueue_script( 'cbxchangelog-edit' );

			wp_enqueue_script( 'cbxchangelog-admin' );

		}//end only for post add/edit screen


		//only for setting page
		if ( $page == 'cbxchangelog-settings' ) {
			wp_enqueue_script( 'jquery' );

			wp_enqueue_media();

			wp_register_script( 'pickr', $vendors_url_part . 'pickr/pickr.min.js', [], $version, true );
			wp_register_script( 'awesome-notifications', $vendors_url_part . 'awesome-notifications/script.js', [], $version, true );
			wp_register_script( 'select2', $vendors_url_part . 'select2/js/select2.full.min.js', [ 'jquery' ], $version, true );
			wp_register_script( 'cbxchangelog-setting', $js_url_part . 'cbxchangelog-setting.js',
				[
					'jquery',
					'select2',
					'pickr',
					'awesome-notifications'
				],
				$version, true );

			$setting_js_vars = apply_filters( 'cbxchangelog_setting_js_vars',
				[
					'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
					'ajax_fail'                => esc_html__( 'Request failed, please reload the page.', 'cbxchangelog' ),
					'nonce'                    => wp_create_nonce( "settingsnonce" ),
					'is_user_logged_in'        => is_user_logged_in() ? 1 : 0,
					'please_select'            => esc_html__( 'Please Select', 'cbxchangelog' ),
					'upload_title'             => esc_html__( 'Window Title', 'cbxchangelog' ),
					'search_placeholder'       => esc_html__( 'Search here', 'cbxchangelog' ),
					'copycmds'                 => [
						'copy'       => esc_html__( 'Copy', 'cbxchangelog' ),
						'copied'     => esc_html__( 'Copied', 'cbxchangelog' ),
						'copy_tip'   => esc_html__( 'Click to copy', 'cbxchangelog' ),
						'copied_tip' => esc_html__( 'Copied to clipboard', 'cbxchangelog' ),
					],
					'confirm_msg'              => esc_html__( 'Are you sure to remove this step?', 'cbxchangelog' ),
					'confirm_msg_all'          => esc_html__( 'Are you sure to remove all steps?', 'cbxchangelog' ),
					'confirm_yes'              => esc_html__( 'Yes', 'cbxchangelog' ),
					'confirm_no'               => esc_html__( 'No', 'cbxchangelog' ),
					'are_you_sure_global'      => esc_html__( 'Are you sure?', 'cbxchangelog' ),
					'are_you_sure_delete_desc' => esc_html__( 'Once you delete, it\'s gone forever. You can not revert it back.', 'cbxchangelog' ),
					'pickr_i18n'               => [
						// Strings visible in the UI
						'ui:dialog'       => esc_html__( 'color picker dialog', 'cbxchangelog' ),
						'btn:toggle'      => esc_html__( 'toggle color picker dialog', 'cbxchangelog' ),
						'btn:swatch'      => esc_html__( 'color swatch', 'cbxchangelog' ),
						'btn:last-color'  => esc_html__( 'use previous color', 'cbxchangelog' ),
						'btn:save'        => esc_html__( 'Save', 'cbxchangelog' ),
						'btn:cancel'      => esc_html__( 'Cancel', 'cbxchangelog' ),
						'btn:clear'       => esc_html__( 'Clear', 'cbxchangelog' ),

						// Strings used for aria-labels
						'aria:btn:save'   => esc_html__( 'save and close', 'cbxchangelog' ),
						'aria:btn:cancel' => esc_html__( 'cancel and close', 'cbxchangelog' ),
						'aria:btn:clear'  => esc_html__( 'clear and close', 'cbxchangelog' ),
						'aria:input'      => esc_html__( 'color input field', 'cbxchangelog' ),
						'aria:palette'    => esc_html__( 'color selection area', 'cbxchangelog' ),
						'aria:hue'        => esc_html__( 'hue selection slider', 'cbxchangelog' ),
						'aria:opacity'    => esc_html__( 'selection slider', 'cbxchangelog' ),
					],
					'awn_options'              => [
						'tip'           => esc_html__( 'Tip', 'cbxchangelog' ),
						'info'          => esc_html__( 'Info', 'cbxchangelog' ),
						'success'       => esc_html__( 'Success', 'cbxchangelog' ),
						'warning'       => esc_html__( 'Attention', 'cbxchangelog' ),
						'alert'         => esc_html__( 'Error', 'cbxchangelog' ),
						'async'         => esc_html__( 'Loading', 'cbxchangelog' ),
						'confirm'       => esc_html__( 'Confirmation', 'cbxchangelog' ),
						'confirmOk'     => esc_html__( 'OK', 'cbxchangelog' ),
						'confirmCancel' => esc_html__( 'Cancel', 'cbxchangelog' )
					],
					'teeny_setting'            => [
						'teeny'         => true,
						'media_buttons' => true,
						'editor_class'  => '',
						'textarea_rows' => 5,
						'quicktags'     => false,
						'menubar'       => false,
					],
					'lang'                     => get_user_locale()
				] );

			wp_localize_script( 'cbxchangelog-setting', 'cbxchangelog_setting', apply_filters( 'cbxchangelog_setting_vars', $setting_js_vars ) );

			wp_enqueue_script( 'select2' );
			wp_enqueue_script( 'pickr' );
			wp_enqueue_script( 'awesome-notifications' );

			wp_enqueue_script( 'cbxchangelog-setting' );


		}//end only for setting page
	}//end enqueue_scripts

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param  mixed  $links  Plugin Action links.
	 *
	 * @return  array
	 */
	public function plugin_action_links( $links ) {
		$action_links = [
			'settings' => '<a style="color:#2153cc !important; font-weight: bold;" href="' . admin_url( 'edit.php?post_type=cbxchangelog&page=cbxchangelog-settings' ) . '" aria-label="' . esc_attr__( 'View settings', 'cbxchangelog' ) . '">' . esc_html__( 'Settings', 'cbxchangelog' ) . '</a>',
		];

		return array_merge( $action_links, $links );
	}//end plugin_action_links

	/**
	 * Filters the array of row meta for each/specific plugin in the Plugins list table.
	 * Appends additional links below each/specific plugin on the plugins page.
	 *
	 * @access  public
	 *
	 * @param  array  $links_array  An array of the plugin's metadata
	 * @param  string  $plugin_file_name  Path to the plugin file
	 * @param  array  $plugin_data  An array of plugin data
	 * @param  string  $status  Status of the plugin
	 *
	 * @return  array       $links_array
	 */
	public function plugin_row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {
		if ( strpos( $plugin_file_name, CBXCHANGELOG_BASE_NAME ) !== false ) {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$links_array[] = '<a target="_blank" style="color:#2153cc !important; font-weight: bold;" href="https://wordpress.org/support/plugin/cbxchangelog/" aria-label="' . esc_attr__( 'Free Support', 'cbxchangelog' ) . '">' . esc_html__( 'Free Support', 'cbxchangelog' ) . '</a>';
			$links_array[] = '<a target="_blank" style="color:#2153cc !important; font-weight: bold;" href="https://wordpress.org/plugins/cbxchangelog/#reviews" aria-label="' . esc_attr__( 'Reviews', 'cbxchangelog' ) . '">' . esc_html__( 'Reviews', 'cbxchangelog' ) . '</a>';
			$links_array[] = '<a target="_blank" style="color:#2153cc !important; font-weight: bold;" href="https://codeboxr.com/doc/cbxchangelog-doc/" aria-label="' . esc_attr__( 'Documentation', 'cbxchangelog' ) . '">' . esc_html__( 'Documentation', 'cbxchangelog' ) . '</a>';


			if ( in_array( 'cbxchangelogpro/cbxchangelogpro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || defined( 'CBXCHANGELOGPRO_PLUGIN_NAME' ) ) {

			} else {
				$links_array[] = '<a target="_blank" style="color:#2153cc !important; font-weight: bold;" href="https://codeboxr.com/product/cbx-changelog-for-wordpress/" aria-label="' . esc_attr__( 'Try Pro Addon', 'cbxchangelog' ) . '">' . esc_html__( 'Try Pro Addon', 'cbxchangelog' ) . '</a>';
			}


		}

		return $links_array;
	}//end plugin_row_meta

	/**
	 * Remove date filter from post type 'cbxchangelog' listing
	 *
	 * @return void
	 */
	public function remove_date_filter() {
		$screen = get_current_screen();
		if ( 'cbxchangelog' == $screen->post_type ) {
			add_filter( 'months_dropdown_results', '__return_empty_array' );
		}
	}//end remove_date_filter

	/**
	 * If we need to do something in upgrader process is completed for changelog plugin
	 *
	 * @param $upgrader_object
	 * @param $options
	 */
	public function plugin_upgrader_process_complete( $upgrader_object, $options ) {
		if ( isset( $options['plugins'] ) && $options['action'] == 'update' && $options['type'] == 'plugin' ) {
			foreach ( $options['plugins'] as $each_plugin ) {
				if ( $each_plugin == CBXCHANGELOG_BASE_NAME ) {
					add_option( 'cbxchangelog_flush_rewrite_rules', 'true' );
					set_transient( 'cbxchangelog_upgraded_notice', 1 );
					break;
				}
			}
		}
	}//end plugin_upgrader_process_complete

	/**
	 * Show a notice to anyone who has just installed the plugin for the first time
	 * This notice shouldn't display to anyone who has just updated this plugin
	 */
	public function plugin_activate_upgrade_notices() {
		if ( get_option( 'cbxchangelog_flush_rewrite_rules' ) == 'true' ) {
			flush_rewrite_rules();
			delete_option( 'cbxchangelog_flush_rewrite_rules' );
		}

		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbxchangelog_activated_notice' ) ) {
			echo '<div class="notice notice-success is-dismissible" style="border-color: #2153cc !important;">';
			echo '<p>' . sprintf( __( 'Thanks for installing/deactivating <strong>CBX Changelog</strong> V%s - Codeboxr Team',
					'cbxchangelog' ),
					CBXCHANGELOG_PLUGIN_VERSION ) . '</p>';
			echo '<p>' . sprintf( __( 'Check <a style="color: #6648fe !important; font-weight: bold;" href="%s" target="_blank">Documentation</a> | Create <a style="color: #6648fe !important; font-weight: bold;" href="%s" target="_blank">Changelog</a>',
					'cbxchangelog' ),
					'https://codeboxr.com/doc/cbxchangelog-doc/',
					admin_url( 'post-new.php?post_type=cbxchangelog' ) ) . '</p>';
			echo '</div>';

			// Delete the transient so we don't keep displaying the activation message
			delete_transient( 'cbxchangelog_activated_notice' );

			$this->pro_addon_compatibility_campaign();
		}

		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbxchangelog_upgraded_notice' ) ) {
			echo '<div class="notice notice-success is-dismissible" style="border-color: #2153cc !important;">';
			echo '<p>' . sprintf( __( 'Thanks for upgrading <strong>CBX Changelog</strong> V%s , enjoy the new features and bug fixes - Codeboxr Team',
					'cbxchangelog' ),
					CBXCHANGELOG_PLUGIN_VERSION ) . '</p>';
			echo '<p>' . sprintf( __( 'Check <a style="color: #6648fe !important; font-weight: bold;" href="%s" target="_blank">Documentation</a> | Create <a style="color: #6648fe !important; font-weight: bold;" href="%s" target="_blank">Changelog</a>', 'cbxchangelog' ),
					'https://codeboxr.com/doc/cbxchangelog-doc/',
					admin_url( 'post-new.php?post_type=cbxchangelog' ) ) . '</p>';
			echo '</div>';

			// Delete the transient so we don't keep displaying the activation message
			delete_transient( 'cbxchangelog_upgraded_notice' );

			$this->pro_addon_compatibility_campaign();
		}
	}//end plugin_activate_upgrade_notices

	/**
	 * Check plugin compatibility and pro addon install campaign
	 */
	public function pro_addon_compatibility_campaign() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		//if the pro addon is active or installed
		if ( in_array( 'cbxchangelogpro/cbxchangelogpro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || defined( 'CBXCHANGELOGPRO_PLUGIN_NAME' ) ) {
			//plugin is activated

			$plugin_version = CBXCHANGELOGPRO_PLUGIN_VERSION;

		} else {
			echo '<div class="notice notice-success is-dismissible" style="border-color: #2153cc !important;"><p>' . sprintf( __( 'CBX Changelog Pro has extended features and more controls, <a style="color: #6648fe !important; font-weight: bold;" target="_blank" href="%s">try it</a>  - Codeboxr Team',
					'cbxchangelog' ), 'https://codeboxr.com/product/cbx-changelog-for-wordpress/#downloadarea/' ) . '</p></div>';
		}
	}//end pro_addon_compatibility_campaign

	/**
	 * Register New Gutenberg block Category if need
	 *
	 * @param $categories
	 * @param $post
	 *
	 * @return mixed
	 */
	public function gutenberg_block_categories( $categories, $post ) {
		$found = false;
		foreach ( $categories as $category ) {
			if ( $category['slug'] == 'codeboxr' ) {
				$found = true;
				break;
			}
		}

		if ( ! $found ) {
			return array_merge(
				$categories,
				[
					[
						'slug'  => 'codeboxr',
						'title' => esc_html__( 'CBX Blocks', 'cbxchangelog' ),
					],
				]
			);
		}

		return $categories;
	}//end gutenberg_block_categories

	/**
	 * Init all gutenberg blocks
	 */
	public function gutenberg_blocks() {
		if ( ! function_exists( 'register_block_type' ) ) {
			// Gutenberg is not active.
			return;
		}


		$this->init_changelog_shortcode_block();

	}//end gutenberg_blocks

	/**
	 * Register bookmark posts block
	 */
	public function init_changelog_shortcode_block() {
		$css_url_part     = CBXCHANGELOG_ROOT_URL . 'assets/css/';
		$js_url_part      = CBXCHANGELOG_ROOT_URL . 'assets/js/';
		$vendors_url_part = CBXCHANGELOG_ROOT_URL . 'assets/vendors/';

		$css_path_part     = CBXCHANGELOG_ROOT_PATH . 'assets/css/';
		$js_path_part      = CBXCHANGELOG_ROOT_PATH . 'assets/js/';
		$vendors_path_part = CBXCHANGELOG_ROOT_PATH . 'assets/vendors/';


		$setting               = $this->settings_api;
		$show_label_default    = intval( $setting->get_option( 'show_label', 'cbxchangelog_general', 1 ) );
		$show_date_default     = intval( $setting->get_option( 'show_date', 'cbxchangelog_general', 1 ) );
		$show_url_default      = intval( $setting->get_option( 'show_url', 'cbxchangelog_general', 1 ) );
		$relative_date_default = intval( $setting->get_option( 'relative_date', 'cbxchangelog_general', 0 ) );

		$layout = $setting->get_option( 'layout', 'cbxchangelog_general', 'prepros' );

		$show_label_default    = ( $show_label_default ) ? true : false;
		$show_date_default     = ( $show_date_default ) ? true : false;
		$show_url_default      = ( $show_url_default ) ? true : false;
		$relative_date_default = ( $relative_date_default ) ? true : false;

		$order_options = [];

		$order_options[] = [
			'label' => esc_html__( 'Descending Order', 'cbxchangelog' ),
			'value' => 'desc',
		];

		$order_options[] = [
			'label' => esc_html__( 'Ascending Order', 'cbxchangelog' ),
			'value' => 'asc',
		];

		$orderby_options   = [];
		$orderby_options[] = [
			'label' => esc_html__( 'Default', 'cbxchangelog' ),
			'value' => 'order',
		];

		$orderby_options[] = [
			'label' => esc_html__( 'Date', 'cbxchangelog' ),
			'value' => 'date',
		];

		$layouts        = CBXChangelogHelper::get_layouts();
		$layout_options = [];
		foreach ( $layouts as $layout_key => $layout_title ) {
			$layout_options[] = [
				'label' => $layout_title,
				'value' => $layout_key,
			];
		}


		wp_register_style( 'cbxchangelog-block', $css_url_part . 'cbxchangelog-block.css', [], filemtime( $css_path_part . 'cbxchangelog-block.css' ) );
		wp_register_script( 'cbxchangelog-block', $js_url_part . 'blocks/cbxchangelog-block.js',
			[
				'wp-blocks',
				'wp-element',
				'wp-components',
				'wp-editor',
				//'jquery',
				//'codeboxrflexiblecountdown-public'
			],
			filemtime( $js_path_part . 'blocks/cbxchangelog-block.js' ) );

		$js_vars = apply_filters( 'cbxchangelog_block_js_vars',
			[
				//'cbxbookmark_lang'        => get_user_locale(),
				'block_title'      => esc_html__( 'CBX Changelog', 'cbxchangelog' ),
				'block_category'   => 'cbxchangelog',
				'block_icon'       => 'universal-access-alt',
				'general_settings' => [
					'heading'         => esc_html__( 'Block Settings', 'cbxchangelog' ),
					'title'           => esc_html__( 'Title', 'cbxchangelog' ),
					'title_desc'      => esc_html__( 'Leave empty to hide', 'cbxchangelog' ),
					'id'              => esc_html__( 'Change Log ID', 'cbxchangelog' ),
					'release'         => esc_html__( 'Release ID', 'cbxchangelog' ),
					'show_label'      => esc_html__( 'Show Label', 'cbxchangelog' ),
					'show_date'       => esc_html__( 'Show Date', 'cbxchangelog' ),
					'show_url'        => esc_html__( 'Show Url', 'cbxchangelog' ),
					'relative_date'   => esc_html__( 'Show Relative Date', 'cbxchangelog' ),
					'layout'          => esc_html__( 'Choose layout', 'cbxchangelog' ),
					'layout_options'  => $layout_options,
					'order'           => esc_html__( 'Order', 'cbxchangelog' ),
					'order_options'   => $order_options,
					'orderby'         => esc_html__( 'Order By', 'cbxchangelog' ),
					'orderby_options' => $orderby_options,
				],
			] );

		wp_localize_script( 'cbxchangelog-block', 'cbxchangelog_block', $js_vars );

		register_block_type( 'codeboxr/cbxchangelog-block',
			[
				'editor_script'   => 'cbxchangelog-block',
				'editor_style'    => 'cbxchangelog-block',
				'attributes'      => apply_filters( 'cbxchangelog_block_attributes',
					[
						'title'         => [
							'type'    => 'string',
							'default' => esc_html__( 'Release logs', 'cbxchangelog' ),
						],
						'id'            => [
							'type'    => 'integer',
							'default' => 0,
						],
						'release'       => [
							'type'    => 'integer',
							'default' => 0,
						],
						'show_label'    => [
							'type'    => 'boolean',
							'default' => $show_label_default,
						],
						'show_date'     => [
							'type'    => 'boolean',
							'default' => $show_date_default,
						],
						'show_url'      => [
							'type'    => 'boolean',
							'default' => $show_url_default,
						],
						'relative_date' => [
							'type'    => 'boolean',
							'default' => $relative_date_default,
						],
						'layout'        => [
							'type'    => 'string',
							'default' => $layout,
						],
						'order'         => [
							'type'    => 'string',
							'default' => 'desc',
						],
						'orderby'       => [
							'type'    => 'string',
							'default' => 'default',
						],

					] ),
				'render_callback' => [ $this, 'cbxchangelog_block_render' ],
			] );
	}//end init_changelog_shortcode_block

	/**
	 * Gutenberg server side render for my bookmark post block
	 *
	 * @param $attr
	 *
	 * @return string
	 */
	public function cbxchangelog_block_render( $attr ) {
		$params = [];

		$params['title'] = isset( $attr['title'] ) ? sanitize_text_field( $attr['title'] ) : '';

		//$params['id']      = isset($attr['id']) ? intval($attr['id']) : 0;
		$params['id']      = isset( $attr['id'] ) ? intval( $attr['id'] ) : 0;
		$params['release'] = isset( $attr['release'] ) ? intval( $attr['release'] ) : 0;


		$params['show_label'] = isset( $attr['show_label'] ) ? $attr['show_label'] : 'true';
		$params['show_label'] = ( $params['show_label'] == 'true' ) ? 1 : 0;

		$params['show_date'] = isset( $attr['show_date'] ) ? $attr['show_date'] : 'true';
		$params['show_date'] = ( $params['show_date'] == 'true' ) ? 1 : 0;

		$params['show_url'] = isset( $attr['show_url'] ) ? $attr['show_url'] : 'true';
		$params['show_url'] = ( $params['show_url'] == 'true' ) ? 1 : 0;

		$params['relative_date'] = isset( $attr['relative_date'] ) ? $attr['relative_date'] : 'true';
		$params['relative_date'] = ( $params['relative_date'] == 'true' ) ? 1 : 0;


		$params['layout']  = isset( $attr['layout'] ) ? sanitize_text_field( $attr['layout'] ) : 'prepros';
		$params['order']   = isset( $attr['order'] ) ? sanitize_text_field( $attr['order'] ) : 'desc';
		$params['orderby'] = isset( $attr['orderby'] ) ? sanitize_text_field( $attr['orderby'] ) : 'default';

		$params = apply_filters( 'cbxchangelog_shortcode_builder_block_attr', $params, $attr );

		$params_html = '';
		foreach ( $params as $key => $value ) {
			$params_html .= ' ' . $key . '="' . $value . '" ';
		}

		return '[cbxchangelog' . $params_html . ']';
	}//end cbxchangelog_block_render

	/**
	 * Enqueue style for block editor
	 */
	public function enqueue_block_editor_assets() {

	}//end enqueue_block_editor_assets


	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param $transient
	 *
	 * @return object $ transient
	 */
	public function pre_set_site_transient_update_plugins_pro_addon( $transient ) {
		// Extra check for 3rd plugins
		if ( isset( $transient->response['cbxchangelogpro/cbxchangelogpro.php'] ) ) {
			return $transient;
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_info = [];
		$all_plugins = get_plugins();
		if ( ! isset( $all_plugins['cbxchangelogpro/cbxchangelogpro.php'] ) ) {
			return $transient;
		} else {
			$plugin_info = $all_plugins['cbxchangelogpro/cbxchangelogpro.php'];
		}


		$remote_version = '1.1.5';

		if ( version_compare( $plugin_info['Version'], $remote_version, '<' ) ) {
			$obj                                                        = new stdClass();
			$obj->slug                                                  = 'cbxchangelogpro';
			$obj->new_version                                           = $remote_version;
			$obj->plugin                                                = 'cbxchangelogpro/cbxchangelogpro.php';
			$obj->url                                                   = '';
			$obj->package                                               = false;
			$obj->name                                                  = 'CBX Changelog Pro Addon';
			$transient->response['cbxchangelogpro/cbxchangelogpro.php'] = $obj;
		}

		return $transient;
	}//end pre_set_site_transient_update_plugins_pro_addon

	/**
	 * Pro Addon update message
	 */
	public function plugin_update_message_pro_addon() {
		echo ' ' . sprintf( __( 'Check how to <a style="color:#9c27b0 !important; font-weight: bold;" href="%s"><strong>Update manually</strong></a> , download the latest version from <a style="color:#9c27b0 !important; font-weight: bold;" href="%s"><strong>My Account</strong></a> section of Codeboxr.com', 'cbxchangelogpro' ), 'https://codeboxr.com/manual-update-pro-addon/', 'https://codeboxr.com/my-account/' );
	}//end plugin_update_message_pro_addon

	/**
	 * Load setting html
	 *
	 * @return void
	 */
	public function settings_reset_load() {
		//security check
		check_ajax_referer( 'settingsnonce', 'security' );

		$msg            = [];
		$msg['html']    = '';
		$msg['message'] = esc_html__( 'Changelog reset setting html loaded successfully', 'cbxchangelog' );
		$msg['success'] = 1;

		if ( ! current_user_can( 'manage_options' ) ) {
			$msg['message'] = esc_html__( 'Sorry, you don\'t have enough permission', 'cbxchangelog' );
			$msg['success'] = 0;
			wp_send_json( $msg );
		}

		$msg['html'] = CBXChangelogHelper::setting_reset_html_table();

		wp_send_json( $msg );
	}//end method settings_reset_load

	/**
	 * Full plugin reset and redirect
	 */
	public function plugin_reset() {
		//security check
		check_ajax_referer( 'settingsnonce', 'security' );

		$url = admin_url( 'edit.php?post_type=cbxchangelog&page=cbxchangelog-settings' );

		$msg            = [];
		$msg['message'] = esc_html__( 'Changelog setting reset scheduled successfully', 'cbxchangelog' );
		$msg['success'] = 1;
		$msg['url']     = $url;

		if ( ! current_user_can( 'manage_options' ) ) {
			$msg['message'] = esc_html__( 'Sorry, you don\'t have enough permission', 'cbxchangelog' );
			$msg['success'] = 0;
			wp_send_json( $msg );
		}

        //before hook
		do_action( 'cbxchangelog_plugin_reset_before' );

		$plugin_resets = wp_unslash( $_POST );

		//delete options
		do_action('cbxchangelog_plugin_options_deleted_before');

		$reset_options = isset( $plugin_resets['reset_options'] ) ? $plugin_resets['reset_options'] : [];
		$option_values = ( is_array( $reset_options ) && sizeof( $reset_options ) > 0 ) ? array_values( $reset_options ) : array_values( CBXChangelogHelper::getAllOptionNamesValues() );

		foreach ( $option_values as $key => $option ) {
			do_action('cbxchangelog_plugin_option_delete_before', $option);
			delete_option($option);
			do_action('cbxchangelog_plugin_option_delete_after', $option);
		}

		do_action('cbxchangelog_plugin_options_deleted_after');
		do_action('cbxchangelog_plugin_options_deleted');
		//end delete options

        //after hook
		do_action( 'cbxchangelog_plugin_reset_after' );


        //general hook
		do_action( 'cbxchangelog_plugin_reset' );

		wp_send_json( $msg );
	}//end plugin_reset

}//end class CBXChangelog_Admin