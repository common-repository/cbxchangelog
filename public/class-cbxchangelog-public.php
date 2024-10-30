<?php

use \Michelf\Markdown;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/public
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXChangelog_Public {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param  string  $plugin_name  The name of the plugin.
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

		//get instance of setting api
		$this->settings_api = new CBXChangelogSettings();

	}//end constructor

	/**
	 * Shortcode init
	 */
	public function init_shortcodes() {
		add_shortcode( 'cbxchangelog', [ $this, 'cbxchangelog_shortcode' ] );
	}//end init_shortcodes

	/**
	 * Shortcode callback
	 */
	public function cbxchangelog_shortcode( $atts ) {
		// normalize attribute keys, lowercase
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		global $post;
		$setting               = $this->settings_api;
		$show_label_default    = $setting->get_option( 'show_label', 'cbxchangelog_general', 1 );
		$show_date_default     = $setting->get_option( 'show_date', 'cbxchangelog_general', 1 );
		$show_url_default      = $setting->get_option( 'show_url', 'cbxchangelog_general', 1 );
		$relative_date_default = $setting->get_option( 'relative_date', 'cbxchangelog_general', 0 );
		$layout                = $setting->get_option( 'layout', 'cbxchangelog_general', 'prepros' );

		$atts = shortcode_atts(
			[
				'title'         => '',
				'id'            => 0,//cbxchangelog type post id
				'release'       => 0,//individual release, index starts from 1
				'show_label'    => $show_label_default,
				'show_date'     => $show_date_default,
				'show_url'      => $show_url_default,
				'relative_date' => $relative_date_default,
				'layout'        => $layout,
				'orderby'       => 'default',//'default = saved order, date = sort by date
				'order'         => 'desc',   //asc, desc
			],
			$atts, 'cbxchangelog' );


		if ( $atts['id'] == 0 && is_singular( 'cbxchangelog' ) ) {
			$atts['id'] = get_the_ID(); //if id missing try to take it from global post id
		}

		$atts['id']      = intval( $atts['id'] );
		$atts['release'] = intval( $atts['release'] );


		if ( $atts['id'] == 0 ) {
			return '';
		}


		$order   = $atts['order'] = strtolower( esc_attr( $atts['order'] ) );
		$orderby = $atts['orderby'] = esc_attr( $atts['orderby'] );
		$title   = $atts['title'] = esc_attr( $atts['title'] );

		if ( $orderby == '' ) {
			$orderby = 'default';
		}
		if ( $order == '' ) {
			$order = 'desc';
		}


		$show_label    = isset( $atts['show_label'] ) ? intval( $atts['show_label'] ) : 1;
		$show_date     = isset( $atts['show_date'] ) ? intval( $atts['show_date'] ) : 1;
		$show_url      = isset( $atts['show_url'] ) ? intval( $atts['show_url'] ) : 1;
		$relative_date = isset( $atts['relative_date'] ) ? intval( $atts['relative_date'] ) : 0;
		$layout        = isset( $atts['layout'] ) ? esc_attr( wp_unslash( $atts['layout'] ) ) : 'prepros';

		if ( $show_label == '' || $show_date == '' || $show_url == '' || $relative_date == '' || $layout == '' ) {
			$meta_extra = get_post_meta( $atts['id'], '_cbxchangelog_extra', true );

			$show_url_meta      = isset( $meta_extra['show_url'] ) ? intval( $meta_extra['show_url'] ) : 1;
			$show_label_meta    = isset( $meta_extra['show_label'] ) ? intval( $meta_extra['show_label'] ) : 1;
			$show_date_meta     = isset( $meta_extra['show_date'] ) ? intval( $meta_extra['show_date'] ) : 1;
			$relative_date_meta = isset( $meta_extra['relative_date'] ) ? intval( $meta_extra['relative_date'] ) : 0;
			$layout_meta        = isset( $meta_extra['layout'] ) ? esc_attr( wp_unslash( $meta_extra['layout'] ) ) : 'prepros';


			if ( $show_url == '' ) {
				$show_url = intval( $show_url_meta ); //take from post meta
			}

			if ( $show_label == '' ) {
				$show_label = intval( $show_label_meta ); //take from post meta
			}

			if ( $show_date == '' ) {
				$show_date = intval( $show_date_meta ); //take from post meta
			}

			if ( $relative_date == '' ) {
				$relative_date = intval( $relative_date_meta ); //take from post meta
			}
			if ( $layout == '' ) {
				$layout = esc_attr( wp_unslash( $layout_meta ) ); //take from post meta
			}

		}


		$meta = get_post_meta( $atts['id'], '_cbxchangelog', true );
		if ( $meta === false ) {
			return '';
		} //nothing saved

		if ( ! is_array( $meta ) ) {
			$meta = [];
		} else {
			$meta = array_filter( $meta );
		}

		$release = intval( $atts['release'] );

		//if we want any party release
		if ( $release > 0 && isset( $meta[ $release - 1 ] ) ) {
			$temp[0] = (array) $meta[ $release - 1 ];
			$meta    = $temp;
		}


		$use_markdown = intval( $setting->get_option( 'use_markdown', 'cbxchangelog_general', 0 ) );
		if ( $use_markdown ) {
			//require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/markdown/Michelf/Markdown.inc.php';
			require plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
		}

		$change_logs = $meta;

		//loop to print

		//$counter                 = sizeof( $change_logs );
		$release_labels_readable = CBXChangelogHelper::cbxchangelog_labels();

		$layout     = 'cbxchangelog_wrapper_' . $layout;
		$hide_label = ( 0 == $show_label ) ? ' cbxchangelog_wrapper_hide_label' : '';

		$output_html = '<div class="cbxchangelog_wrapper ' . $layout . '">';

		if ( $title != '' ) {
			$output_html .= '<h2 class="cbxchangelog_shortcode_title">' . $title . '</h2>';
		}


		if ( $orderby == 'date' ) {
			$order_dir = SORT_DESC;
			if ( $order == 'asc' ) {
				$order_dir = SORT_ASC;
			}
			array_multisort( array_column( $change_logs, 'date' ), $order_dir, $change_logs );
		} elseif ( $orderby == 'default' ) {

			if ( $order == 'asc' ) {
				$change_logs = array_reverse( $change_logs );
			}
		}

		$change_logs = apply_filters( 'cbxchangelog_releases_shortcode', $change_logs, $atts );

		$date_format = apply_filters( 'cbxchangelog_release_date_format', get_option( 'date_format' ));


		foreach ( $change_logs as $index => $change_log ) {
			$change_log = apply_filters( 'cbxchangelog_release_shortcode', $change_log, $atts );

			$output_html .= '<div class="cbxchangelog_release" id="cbxchangelog_release_' . $atts['id'] . '_' . ( $index + 1 ) . '">';

			$version      = isset( $change_log['version'] ) ? esc_attr( $change_log['version'] ) : '';
			$url          = isset( $change_log['url'] ) ? esc_url( $change_log['url'] ) : '';
			$date         = isset( $change_log['date'] ) ? esc_attr( $change_log['date'] ) : '';
			$release_note = isset( $change_log['note'] ) ? esc_textarea( $change_log['note'] ) : '';

			$output_html .= '<div class="cbxchangelog_release_header">';
			$output_html .= '<div class="cbxchangelog_version">' . $version . '</div>';

			if ( $show_date && $date ) {
				if ( $relative_date ) {
					$output_html .= '<div class="cbxchangelog_date"><small>' . CBXChangelogHelper::getChangelogHumanReadableTime( strtotime( $date ) ) . '</small></div>';
				} else {
					$output_html .= '<div class="cbxchangelog_date"><small>' . apply_filters('cbxchangelog_release_date', date_i18n(  $date_format , strtotime( $date ) ), $date, $date_format) . '</small></div>';
				}
			}
			$output_html .= '</div>';//cbxchangelog_release_header
			$output_html .= '<div class="cbxchangelog_release_inner">';
			if ( $release_note != '' ) {
				$release_note = do_shortcode( $release_note );
				if ( $use_markdown ) {
					$release_note = $this->do_parsemarkdown( $release_note );
				}
				$output_html .= '<div class="cbxchangelog_note">' . wpautop( $release_note ) . '</div>';
			}


			$feature = isset( $change_log['feature'] ) ? $change_log['feature'] : [];
			$feature = ( ! is_array( $feature ) ) ? [] : array_filter( $feature );
			$label   = isset( $change_log['label'] ) ? $change_log['label'] : [];
			$label   = ( ! is_array( $label ) ) ? [] : array_filter( $label );

			if ( sizeof( $feature ) > 0 ) {

				$output_html .= '<div class="cbxchangelog_features' . $hide_label . '">';
				foreach ( $feature as $f_index => $single_feature ) {
					$found_label    = isset( $label[ $f_index ] ) ? $label[ $f_index ] : 'added';
					$single_feature = esc_html( $single_feature );
					if ( $single_feature != '' ) {

						$single_feature = do_shortcode( $single_feature );
						if ( $use_markdown ) {
							$single_feature = $this->do_parsemarkdown( $single_feature );
						}
						$output_html .= ' <div class="cbxchangelog_log">';

						if ( $show_label ) {

							$label_name  = isset( $release_labels_readable[ $found_label ] ) ? $release_labels_readable[ $found_label ] : $found_label;
							$output_html .= '<div class="cbxchangelog_log-label cbxchangelog_log_label_' . esc_attr( $found_label ) . '">' . esc_attr( $label_name ) . '</div>';
						}

						$output_html .= '<div class="cbxchangelog_feature">' . $single_feature . '</div>';
						$output_html .= '</div>';//cbxchangelog_log
					}
				}
				$output_html .= '</div>'; //cbxchangelog_features
			}

			//handle url
			if ( $show_url && $url != '' ) {

				$url_target = apply_filters( 'cbxchangelog_url_target', ' target="_blank" ', $url, $change_log, $index, $atts['id'] );

				$output_html .= '<div class="cbxchangelog_features_url">';
				$output_html .= '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAABmJLR0QA/wD/AP+gvaeTAAAB30lEQVRIia3Wv2sUQRjG8c/dRUQbRawVogYUjHp97LUQFMTO/AliGXKEJApaKFgKEkgsxMYfWNgIFoJFRKNRLGyvE5MyYHGixdyR2WF3cuvlhYHded95v/vOPvPuNoxuLVzFZUygh3U8xrtdyE8/8Vf8rRgr2DsqpI3NDGQwlqExAuQNDkVzH/FSqGAaRyLf+f+FpJXcEd7VwA6jG/kf7QZkviJ2MYpZbdaAHMAVYXu+9OcWMqA/0XWrImYoO5XxjWHNdkVPxoZI2MBFXBLk/Fs4JyuZNbdxLrp/sRPkGD4ol22vnzDd/rkkbq0kpmBtbFRA4nE9WtNJfBs4vhMkVdc67glS3Yrmb/TXzCbxm4rbNxRkUVE548KJv499mCmp5GxdyAz244EggPEovoVbSfwvnKkLme37bkZzW1jqV/OtBDKZg0yUQDqRfzrxlY2fOJ2DtIQXHS+aS2KagpR7FZD3OJqDwLVk0d1M7Emhgb7GWzzEBUN+BZ5GkE/yfSnXdrLWxIno/pViM4xtHt+FhrokVH6wDmzVdkULGUh6GNt1IISTPkjQFT5aA2sJTz4yBKaSRF3hEHYUW/1IkIEtK5ftrkIIPxQ52GdF0dS2VP9TQheYxB78wHM8U63GoewfAo7YbZrQf6sAAAAASUVORK5CYII="/><a href="' . $url . '" ' . $url_target . '>' . esc_html__( 'More details about this release',
						'cbxchangelog' ) . '</a>';
				$output_html .= '</div>';
			}


			$output_html .= '</div>'; //cbxchangelog_release_inner
			$output_html .= '</div>'; //.cbxchangelog_release
		}

		$output_html .= '</div>'; //.cbxchangelog_wrapper

		return apply_filters( 'cbxchangelog_releases_shortcode_html', $output_html, $atts );
	}//end cbxchangelog_shortcode

	/**
	 * append the changelog history to any changelog type post type details
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function append_cbxchangelog( $content ) {
		if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) ) {
			return $content;
		}

		$settings       = $this->settings_api;
		$changelog_auto = absint( $settings->get_option( 'changelog_auto', 'cbxchangelog_general', 1 ) );

		if ( is_singular( 'cbxchangelog' ) && $changelog_auto ) {


			global $post;
			$post_id = absint( $post->ID );
			if ( $post_id > 0 ) {
				$meta_extra = get_post_meta( $post_id, '_cbxchangelog_extra', true );

				if ( ! is_array( $meta_extra ) ) {
					$meta_extra = [];
				}


				$meta_extra['show_label']    = $show_label = isset( $meta_extra['show_label'] ) ? intval( $meta_extra['show_label'] ) : 1;
				$meta_extra['show_date']     = $show_date = isset( $meta_extra['show_date'] ) ? intval( $meta_extra['show_date'] ) : 1;
				$meta_extra['relative_date'] = $relative_date = isset( $meta_extra['relative_date'] ) ? intval( $meta_extra['relative_date'] ) : 0;
				$meta_extra['layout']        = $layout = isset( $meta_extra['layout'] ) ? esc_attr( wp_unslash( $meta_extra['layout'] ) ) : 'prepros';

				//'show_label' => $show_label_default,
				//'show_date'  => $show_date_default

				$content_shortcode = do_shortcode( '[cbxchangelog id="' . $post_id . '" show_label="' . $show_label . '" show_date="' . $show_date . '" relative_date= "' . $relative_date . '" layout= "' . $layout . '"]' );

				$content .= '<div class="cbxchangelog_shortcode_content">' . $content_shortcode . '</div>';
			}
		}

		return $content;
	}//end append_cbxchangelog

	/**
	 * Process markdown
	 *
	 * @param $text
	 *
	 * @return mixed
	 */
	public function do_parsemarkdown( $text ) {
		//process markdown text

		return Markdown::defaultTransform( $text );

	}//end do_parsemarkdown

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$version = $this->version;

		$cbxchangelog_public_css = plugin_dir_url( __FILE__ ) . '../assets/css/cbxchangelog-public.css';
		$cbxchangelog_public_css = apply_filters( 'cbxchangelog_pubic_css', $cbxchangelog_public_css );

		wp_register_style( 'cbxchangelog-public-css', $cbxchangelog_public_css, [], $version, 'all' );
		wp_enqueue_style( 'cbxchangelog-public-css' );

		do_action( 'cbxchangelog_enqueue_pro_style' );
	}//end enqueue_styles

	/**
	 * unused method
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cbxchangelog-public.js', array( 'jquery' ), $this->version, false );
	}//end method enqueue_scripts

	/**
	 * Initialize the widgets
	 */
	function init_widgets() {
		register_widget( 'CBXChangelogWidget' );

	}//end method init_widgets

	/**
	 * Init elementor widget
	 *
	 * @throws Exception
	 */
	public function init_elementor_widgets() {
		if ( ! class_exists( 'CBXChangeLog_ElemWidget' ) ) {
			//include the file
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor-elements/class-cbxchangelog-elemwidget.php';
		}

		//register the widget
		\Elementor\Plugin::instance()->widgets_manager->register( new CBXChangeLogElemWidget\Widgets\CBXChangeLog_ElemWidget() );
	}//end widgets_registered

	/**
	 * Add new category to elementor
	 *
	 * @param $elements_manager
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'codeboxr',
			[
				'title' => esc_html__( 'Codeboxr Widgets', 'cbxchangelog' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}//end add_elementor_widget_categories

	/**
	 * Load Elementor Custom Icon
	 */
	function elementor_icon_loader() {
		wp_register_style( 'cbxchangelog_elementor_icon', CBXCHANGELOG_ROOT_URL . 'widgets/elementor-elements/elementor-icon/icon.css', false, CBXCHANGELOG_PLUGIN_VERSION );
		wp_enqueue_style( 'cbxchangelog_elementor_icon' );

	}//end elementor_icon_loader

	/**
	 * Before VC Init
	 */
	public function vc_before_init_actions() {
		if ( ! class_exists( 'CBXChangeLog_WPBWidget' ) ) {
			require_once CBXCHANGELOG_ROOT_PATH . 'widgets/vc-element/class-cbxchangelog-wpbwidget.php';
		}

		new CBXChangeLog_WPBWidget();
	}// end method vc_before_init_actions
}//end CBXChangelog_Public
