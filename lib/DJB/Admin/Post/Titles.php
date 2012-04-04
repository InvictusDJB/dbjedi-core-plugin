<?php

namespace DJB\Admin\Post;

class Titles extends \DJB\Admin\Post {
	public static $post_type = 'djb-title';
	public static $parent_menu = 'djb-maa';
	public static $plural = 'Titles';
	public static $singular = 'Title';
	public static $supports = array(
		'title',
		'custom-fields',
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

	/**
	 * set the ordering for the query
	 */
	public static function get_posts( &$query ) {
		if( $query->query_vars['post_type'] === static::$post_type ) {
			$query->set('orderby', $_GET['orderby'] ?: 'menu_order');
			$query->set('order', $_GET['order'] ?: 'asc');
		}//end if
	}//end get_posts

}//end class DJB\Admin\Post\Titles
