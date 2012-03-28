<?php

namespace DJB\Admin;

class Departments extends Post {
	public static $post_type = 'djb-department';
	public static $parent_menu = 'djb-academy';
	public static $plural = 'Departments';
	public static $singular = 'Department';

	/**
	 * Set up the columns that appear on the list page
	 */
	public static function admin_columns( $old_columns ) {
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['title'] = _x(static::$singular, 'column name');
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

	public static function supports() {
		return array(
			'title',
			'editor'
			'revisions',
			'custom-fields',
		);
	}//end supports
}//end class DJB\Admin\Departments
