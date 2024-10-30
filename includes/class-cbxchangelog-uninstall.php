<?php
/**
 * Fired during plugin uninstall/delete.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      1.0.0
 * @package    CBXChangelog
 * @subpackage CBXChangelog/includes
 * @author     CBX Team  <info@codeboxr.com>
 */
class CBXChangelog_Uninstall {

	/**
	 * Uninstall plugin functionality
	 *
	 *
	 * @since    1.1.3
	 */
	public static function uninstall() {
		// For the regular site.
		if ( ! is_multisite() ) {
			self::uninstall_tasks();
		}
		else{
            //for multi site
			global $wpdb;

			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			$original_blog_id = get_current_blog_id();

			foreach ( $blog_ids as $blog_id )   {
				switch_to_blog( $blog_id );

				self::uninstall_tasks();
			}

			switch_to_blog( $original_blog_id );
		}
	}//end method uninstall

    /**
     * Do the necessary uninstall tasks
     *
     * @since    1.1.3
     * @return void
     */
	public static function uninstall_tasks(){
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

		$settings = new CBXChangelogSettings();
		$delete_global_config = $settings->get_option( 'delete_global_config', 'cbxchangelog_tools', 'no' );

		if ( $delete_global_config == 'yes' ) {
            //before hook
			do_action( 'cbxchangelog_plugin_uninstall_before' );

			//delete options
			$option_values = CBXChangelogHelper::getAllOptionNames();

			do_action('cbxchangelog_plugin_options_deleted_before');

			foreach ( $option_values as $key => $option_value ) {
				$option = $option_value['option_name'];

				do_action('cbxchangelog_plugin_option_delete_before', $option);
				delete_option( $option );
				do_action('cbxchangelog_plugin_option_delete_after', $option);
			}

			do_action('cbxchangelog_plugin_options_deleted_after');
			do_action('cbxchangelog_plugin_options_deleted');
			//end delete options

            //after hook
			do_action( 'cbxchangelog_plugin_uninstall_after' );

            //general hook
			do_action( 'cbxchangelog_plugin_uninstall' );
		}
	}//end method uninstall_tasks
}//end class CBXChangelog_Uninstall
