<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/templates/admin
 */


if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="cbxshortcode-wrap">
	<?php
	echo '<span data-clipboard-text=\'[cbxchangelog id="' . $post_id . '"]\' title="' . esc_html__( "Click to clipboard",
			"cbxchangelog" ) . '" id="cbxchangelogshortcode-' . $post_id . '" class="cbxshortcode cbxshortcode-edit cbxshortcode-' . intval( $post_id ) . '">[cbxchangelog id="' . intval( $post_id ) . '"]</span>';
	echo '<span class="cbxballon_ctp_btn cbxballon_ctp" aria-label="' . esc_html__( 'Click to copy', 'cbxchangelog' ) . '" data-balloon-pos="down"><i></i></span>';
	?>
</div>