<?php

/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxchangelog
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * The code that runs during plugin uninstall.
 */
function uninstall_cbxchangelog() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxchangelog-settings.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxchangelog-helper.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxchangelog-uninstall.php';

	CBXChangelog_Uninstall::uninstall();
}//end function uninstall_cbxchangelog

if (! defined( 'CBXCHANGELOG_PLUGIN_NAME' ) ) {
	uninstall_cbxchangelog();
}