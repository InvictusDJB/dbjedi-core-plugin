<?php

namespace DJB\Admin;

class Courses extends Post {
	public static $post_type = 'djb-course';
	public static $parent_menu = 'djb-academy';
	public static $plural = 'Courses';
	public static $singular = 'Course';
	public static $supports = array(
		'title',
		'editor',
		'custom-fields',
		'revisions',
	);

	/**
	 * Set up the columns that appear on the list page
	 */
	public static function admin_columns( $old_columns ) {
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['title'] = _x('Course', 'column name');

		return $columns;
	}//end admin_columns

	/**
	 * set the ordering for the query
	 */
	public static function get_posts( &$query ) {
		if( $query->query_vars['post_type'] === static::$post_type ) {
			$query->set('orderby', 'title');
			$query->set('order', 'asc');
		}//end if
	}//end get_posts

}//end class DJB\Admin\Courses
