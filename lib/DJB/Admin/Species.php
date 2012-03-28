<?php

namespace DJB\Admin;

class Species extends Post {
	public static $post_type = 'djb-species';
	public static $plural = 'Species';
	public static $singular = 'Species';
	public static $supports = array(
		'title',
	);

	/**
	 * Set up the columns that appear on the list page
	 */
	public static function admin_columns( $old_columns ) {
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['title'] = _x('Species', 'column name');

		return $columns;
	}//end admin_columns
}//end class DJB\Admin\Species
