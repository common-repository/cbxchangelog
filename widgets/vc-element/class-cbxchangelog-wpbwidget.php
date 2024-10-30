<?php
// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class CBXChangeLog_WPBWidget extends WPBakeryShortCode {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'bakery_shortcode_mapping' ], 12 );
	}// /end of constructor

	/**
	 * Element Mapping
	 */
	public function bakery_shortcode_mapping() {
		$layout      = \CbxchangelogHelper::get_layouts();
		$layout_meta = \CbxchangelogHelper::get_layouts_for_meta();
		$layouts     = array_merge( $layout_meta, $layout );
		$layout      = array_flip( $layouts );

		// Map the block with vc_map()
		vc_map( [
			"name"        => esc_html__( "CBX Changelog", 'cbxchangelog' ),
			"description" => esc_html__( "CBX Changelog Widget", 'cbxchangelog' ),
			"base"        => "cbxchangelog",
			"icon"        => CBXCHANGELOG_ROOT_URL . 'assets/images/icon.png',
			"category"    => esc_html__( 'CBX Widgets', 'cbxchangelog' ),
			"params"      => apply_filters( 'cbxchangelog_wpbakery_params', [

					[
						'type'        => 'textfield',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Changelog ID', 'cbxchangelog' ),
						'param_name'  => 'id',
						'std'         => 0,
						'description' => esc_html__( 'Set Post ID(Change log post type or other supported)', 'cbxchangelog' ),
					],

					[
						'type'        => 'textfield',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Release', 'cbxchangelog' ),
						'param_name'  => 'release',
						'std'         => '0',
					],
					[
						"type"        => "dropdown",
						'admin_label' => false,
						"heading"     => esc_html__( "Show Label", 'cbxchangelog' ),
						"param_name"  => "show_label",
						'value'       => [
							esc_html__( 'Choose from post meta', 'cbxchangelog' ) => '',
							esc_html__( 'Yes', 'cbxchangelog' )                   => 1,
							esc_html__( 'No', 'cbxchangelog' )                    => 0,
						],
						'std'         => 1,
					],
					[
						"type"        => "dropdown",
						'admin_label' => false,
						"heading"     => esc_html__( "Show Url", 'cbxchangelog' ),
						"param_name"  => "show_url",
						'value'       => [
							esc_html__( 'Choose from post meta', 'cbxchangelog' ) => '',
							esc_html__( 'Yes', 'cbxchangelog' )                   => 1,
							esc_html__( 'No', 'cbxchangelog' )                    => 0,
						],
						'std'         => 1,
					],
					[
						"type"        => "dropdown",
						'admin_label' => false,
						"heading"     => esc_html__( "Show date", 'cbxchangelog' ),
						"param_name"  => "show_date",
						'value'       => [
							esc_html__( 'Choose from post meta', 'cbxchangelog' ) => '',
							esc_html__( 'Yes', 'cbxchangelog' )                   => 1,
							esc_html__( 'No', 'cbxchangelog' )                    => 0,
						],
						'std'         => 1,
					],

					[
						"type"        => "dropdown",
						'admin_label' => false,
						"heading"     => esc_html__( "Show Relative date", 'cbxchangelog' ),
						"param_name"  => "relative_date",
						'value'       => [
							esc_html__( 'Choose from post meta', 'cbxchangelog' ) => '',
							esc_html__( 'Yes', 'cbxchangelog' )                   => 1,
							esc_html__( 'No', 'cbxchangelog' )                    => 0,
						],
						'std'         => 0,
					],
					[
						"type"        => "dropdown",
						'admin_label' => false,
						"heading"     => esc_html__( "Choose layout", 'cbxchangelog' ),
						"param_name"  => "layout",
						'value'       => $layout,
						'std'         => 'prepros',
					],
					[
						"type"        => "dropdown",
						'admin_label' => false,
						"heading"     => esc_html__( "Order By", 'cbxchangelog' ),
						"param_name"  => "orderby",
						'value'       => [
							esc_html__( 'Default', 'cbxchangelog' ) => 'default',
							esc_html__( 'Date', 'cbxchangelog' )    => 'date',
						],
						'std'         => 'default',
					],
					[
						"type"        => "dropdown",
						'admin_label' => false,
						"heading"     => esc_html__( "Order", 'cbxchangelog' ),
						"param_name"  => "orderby",
						'value'       => [
							esc_html__( 'Desc', 'cbxchangelog' ) => 'desc',
							esc_html__( 'Asc', 'cbxchangelog' )  => 'asc',
						],
						'std'         => 'desc',
					]
				]
			)
		] );
	}//end bakery_shortcode_mapping
}// end class CBXChangeLog_WPBWidget