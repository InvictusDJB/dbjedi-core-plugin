<?php

namespace DJB\Admin\Post;

class Titles extends \DJB\Admin\Post {
	public static $post_type = 'djb-title';
	public static $plural = 'Titles';
	public static $singular = 'Title';
	public static $supports = array(
		'title',
	);

	/**
	 * Set up the columns that appear on the list page
	 */
	public static function admin_columns( $old_columns ) {
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['title'] = _x(static::$singular, 'column name');

		return $columns;
	}//end admin_columns
}//end class DJB\Admin\Post\Titles
