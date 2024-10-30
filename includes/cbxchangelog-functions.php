<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'cbxchangelog_get_single' ) ) {
	function cbxchangelog_get_single( $post_id = 0 ) {
		$post_id = absint( $post_id );

		if ( $post_id == 0 ) {
			return [];
		}

		$meta_changelog = get_post_meta( $post_id, '_cbxchangelog', true );
		if ( is_array( $meta_changelog ) ) {
			$meta_extra = get_post_meta( $post_id, '_cbxchangelog_extra', true );
			if ( ! is_array( $meta_extra ) ) {
				$meta_extra = [];
			}

			$meta = [
				'cbxchangelogs_data' => $meta_changelog,
				'cbxchangelog_extra' => $meta_extra,
			];

			return $meta;
		}

		return [];
	}//end function cbxchangelog_get_single
}