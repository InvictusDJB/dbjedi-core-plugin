<?php

namespace DJB\Admin\Post;

class Departments extends \DJB\Admin\Post {
	public static $post_type = 'djb-department';
	public static $parent_menu = 'djb-academy';
	public static $plural = 'Departments';
	public static $singular = 'Department';
	public static $supports = array(
		'title',
		'editor',
		'revisions',
		'custom-fields',
	);

	/**
	 * Set up the columns that appear on the list page
	 */
	public static function admin_columns( $old_columns ) {
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['title'] = _x(static::$singular, 'column name');
		$columns['instructor'] = __('Instructor');

		return $columns;
	}//end admin_columns

	/**
	 * echo the contents of custom columns defined in admin_columns
	 */
	public static function admin_custom_column( $column, $post_id ) {
		$post = get_post( $post_id );

		switch( $column ) {
			case 'instructor':
				if( $user_id = get_post_meta( $post_id, 'instructor_id', true ) ) {
					echo \DJB\Links::dossier( $user_id );
				}//end if
				break;
		}//end switch
	}//end admin_columns
}//end class DJB\Admin\Post\Departments
