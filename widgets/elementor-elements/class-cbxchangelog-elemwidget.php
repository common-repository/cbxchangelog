<?php

namespace CBXChangeLogElemWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CBX Changelog Widget
 */
class CBXChangeLog_ElemWidget extends \Elementor\Widget_Base {

	/**
	 * Retrieve widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'cbxchangelog_single';
	}

	/**
	 * Retrieve widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'CBXChangelog Widget', 'cbxchangelog' );
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the widget categories.
	 *
	 * @return array Widget categories.
	 * @since  1.0.10
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'codeboxr' ];
	}

	/**
	 * Retrieve widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'cbxchangelog-icon';
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_cbxchangelog',
			[
				'label' => esc_html__( 'CBXChangelog Widget Setting', 'cbxchangelog' ),
			]
		);
		$this->add_control(
			'cbxchangelog_id',
			[
				'label'       => esc_html__( 'Changelog ID', 'cbxchangelog' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 0,
				'description' => esc_html__( 'Set Post ID(Change log post type or other supported)', 'cbxchangelog' ),
			]
		);

		$this->add_control(
			'cbxchangelog_release',
			[
				'label'   => esc_html__( 'Release', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 0,
			]
		);

		$this->add_control(
			'cbxchangelog_show_label',
			[
				'label'   => esc_html__( 'Show label', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
					'1' => esc_html__( 'Yes', 'cbxchangelog' ),
					'0' => esc_html__( 'No', 'cbxchangelog' ),
				],
				'default' => 1,
			]
		);

		$this->add_control(
			'cbxchangelog_show_url',
			[
				'label'   => esc_html__( 'Show Url', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
					'1' => esc_html__( 'Yes', 'cbxchangelog' ),
					'0' => esc_html__( 'No', 'cbxchangelog' ),
				],
				'default' => 1,
			]
		);

		$this->add_control(
			'cbxchangelog_show_date',
			[
				'label'   => esc_html__( 'Show date', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
					'1' => esc_html__( 'Yes', 'cbxchangelog' ),
					'0' => esc_html__( 'No', 'cbxchangelog' ),
				],
				'default' => 1,
			]
		);

		$this->add_control(
			'cbxchangelog_relative_date',
			[
				'label'   => esc_html__( 'Show Relative date', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
					'1' => esc_html__( 'Yes', 'cbxchangelog' ),
					'0' => esc_html__( 'No', 'cbxchangelog' ),
				],
				'default' => 0,
			]
		);

		$layout      = \CbxchangelogHelper::get_layouts();
		$layout_meta = \CbxchangelogHelper::get_layouts_for_meta();
		$layouts     = array_merge( $layout_meta, $layout );
		$this->add_control(
			'cbxchangelog_layout',
			[
				'label'   => esc_html__( 'Choose layout', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $layouts,
				'default' => 'prepros',
			]
		);

		$this->add_control(
			'cbxchangelog_orderby',
			[
				'label'   => esc_html__( 'Order By', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'cbxchangelog' ),
					'date'    => esc_html__( 'Date', 'cbxchangelog' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'cbxchangelog_order',
			[
				'label'   => esc_html__( 'Order', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'desc' => esc_html__( 'Desc', 'cbxchangelog' ),
					'asc'  => esc_html__( 'Asc', 'cbxchangelog' ),
				],
				'default' => 'desc',
			]
		);


		$this->end_controls_section();
	}//end method _register_controls


	/**
	 * Renderwidget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		$id      = isset( $settings['cbxchangelog_id'] ) ? intval( $settings['cbxchangelog_id'] ) : 0;
		$release = isset( $settings['cbxchangelog_release'] ) ? intval( $settings['cbxchangelog_release'] ) : 0;

		$show_url      = isset( $settings['cbxchangelog_show_url'] ) ? esc_attr( wp_unslash( $settings['cbxchangelog_show_url'] ) ) : 1;
		$show_label    = isset( $settings['cbxchangelog_show_label'] ) ? esc_attr( wp_unslash( $settings['cbxchangelog_show_label'] ) ) : 1;
		$show_date     = isset( $settings['cbxchangelog_show_date'] ) ? esc_attr( wp_unslash( $settings['cbxchangelog_show_date'] ) ) : 1;
		$relative_date = isset( $settings['cbxchangelog_relative_date'] ) ? esc_attr( wp_unslash( $settings['cbxchangelog_relative_date'] ) ) : 0;
		$layout        = isset( $settings['cbxchangelog_layout'] ) ? esc_attr( wp_unslash( $settings['cbxchangelog_layout'] ) ) : 'prepros';
		$orderby       = isset( $settings['cbxchangelog_orderby'] ) ? esc_attr( wp_unslash( $settings['cbxchangelog_orderby'] ) ) : 'defa ult'; //default, date
		$order         = isset( $settings['cbxchangelog_order'] ) ? esc_attr( wp_unslash( $settings['cbxchangelog_order'] ) ) : 'desc';         //desc, asc

		if ( $orderby == '' ) {
			$orderby = 'default';
		}
		if ( $order == '' ) {
			$order = 'desc';
		}

		if ( intval( $id ) <= 0 && ( false !== get_post_status( $id ) ) ) {
			esc_html_e( 'Set Post ID(Change log post type or other supported)', 'cbxchangelog' );
		} else {
			echo do_shortcode( '[cbxchangelog id="' . $id . '" release="' . $release . '" orderby="' . $orderby . '" order="' . $order . '" show_label="' . $show_label . '" show_url="' . $show_url . '" show_date="' . $show_date . '" relative_date="' . $relative_date . '" layout="' . $layout . '" ]' );
		}
	}//end method render
}//end method CBXChangeLog_ElemWidget
