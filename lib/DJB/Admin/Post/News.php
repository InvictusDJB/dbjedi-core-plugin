<?php

namespace DJB\Admin\Post;

class News extends \DJB\Admin\Post {
	public static $post_type = 'post';
	public static $plural = 'News';
	public static $singular = 'News';

	/**
	 * Set up the columns that appear on the list page
	 */
	public static function admin_columns( $old_columns ) {
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['post_title'] = _x('Article', 'column name');
		$columns['post_author'] = __('Author');

		return $columns;
	}//end admin_columns

	/**
	 * echo the contents of custom columns defined in admin_columns
	 */
	public static function admin_custom_column( $column, $post_id ) {
		$post = get_post( $post_id );

		switch( $column ) {
			case 'post_author':
				if( $user_id = get_post_meta( $post_id, 'post_author', true ) ) {
					echo \DJB\Links::dossier( $user_id );
				}//end if
				break;
		}//end switch
	}//end admin_columns

	/**
	 * set the ordering for the query
	 */
	public static function get_posts( &$query ) {
		if( $query->query_vars['post_type'] === static::$post_type ) {
			$query->set('orderby', $_GET['orderby'] ?: 'post_title');
			$query->set('order', $_GET['order'] ?: 'asc');
		}//end if
	}//end get_posts

}//end class DJB\Admin\Post\Courses
