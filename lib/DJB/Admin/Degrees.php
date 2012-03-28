<?php

namespace DJB\Admin;

class Degrees extends Post {
	public static $post_type = 'djb-degree';
	public static $parent_menu = 'djb-academy';
	public static $plural = 'Degrees';
	public static $singular = 'Degree';
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
		$columns['title'] = _x('Degree', 'column name');
		$columns['abbr'] = __('Abbreviation');
		$columns['menu_order'] = __('Sort Order');

		return $columns;
	}//end admin_columns

	/**
	 * echo the contents of custom columns defined in admin_columns
	 */
	public static function admin_custom_column( $column, $post_id ) {
		$post = get_post( $post_id );

		switch( $column ) {
			case 'abbr':
				echo get_post_meta( $post_id, 'abbr', true );
				break;
			case 'menu_order':
				echo $post->menu_order;
				break;
		}//end switch
	}//end admin_columns

	/**
	 * set the ordering for the query
	 */
	public static function get_posts( &$query ) {
		if( $query->query_vars['post_type'] === static::$post_type ) {
			$query->set('orderby', 'menu_order');
			$query->set('order', 'desc');
		}//end if
	}//end get_posts
}//end class DJB\Admin\Degrees
