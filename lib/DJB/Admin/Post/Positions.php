<?php

namespace DJB\Admin\Post;

class Positions extends \DJB\Admin\Post {
	public static $post_type = 'djb-position';
	public static $parent_menu = 'djb-maa';
	public static $plural = 'Positions';
	public static $singular = 'Position';
	public static $supports = array(
		'title',
		'editor',
		'revisions',
	);
	public static $hierarchical = true;

	/**
	 * Set up the columns that appear on the list page
	 */
	public static function admin_columns( $old_columns ) {
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['title'] = _x(static::$singular, 'column name');
		$columns['abbr'] = __('Abbreviation');
		$columns['type'] = __('Type');

		return $columns;
	}//end admin_columns

	/**
	 * echo the contents of custom columns defined in admin_columns
	 */
	public static function admin_custom_column( $column, $post_id ) {
		switch( $column ) {
			case 'abbr':
				echo get_post_meta( $post_id, 'abbr', true );
				break;
			case 'type':
				echo get_post_meta( $post_id, 'type', true );
				break;
		}//end switch
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

}//end class DJB\Admin\Post\Positions
