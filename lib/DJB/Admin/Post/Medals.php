<?php

namespace DJB\Admin\Post;

class Medals extends \DJB\Admin\Post {
	public static $post_type = 'djb-medal';
	public static $plural = 'Medals';
	public static $singular = 'Medal';
	public static $supports = array(
		'title',
		'custom-fields',
		'page-attributes',
	);
	public static $hierarchical = true;


	/**
	 * Set up the columns that appear on the list page
	 */
	public static function admin_columns( $old_columns ) {
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['image'] = __('');
		$columns['title'] = _x('Medals', 'column name');
		$columns['abbr'] = __('Abbr');
		$columns['group_abbr'] = __('Group Abbr');
		$columns['quantity'] = __('Quantity');
		$columns['menu_order'] = __('Sort Order');
		$columns['group_sort'] = __('Group Sort');

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
			case 'image':
				$image = get_post_meta( $post_id, 'image', true );
				$image_base = \DJB\WordPress::images( static::$post_type );
				$image_url = $image_base . '/' . $image;
				$image_path = \DJB\WordPress::root_dir() . $image_url;

				if( 
					   ! $post->post_parent 
					&& $image_base
					&& $image 
					&& file_exists( $image_path )
				) {
					echo "<img src='".\DJB\WordPress::images( static::$post_type )."/{$image}'/>";
				}//end if
				break;
			case 'group_abbr':
				echo get_post_meta( $post_id, 'group_abbr', true );
				break;
			case 'group_sort':
				$group_sort = get_post_meta( $post_id, 'group_sort', true );
				echo $group_sort ?: '';
				break;
			case 'quantity':
				$quantity = get_post_meta( $post_id, 'quantity', true );
				echo $quantity ?: '';
				break;
			case 'menu_order':
				if( ! $post->post_parent ) {
					echo $post->menu_order;
				}//end if
				break;
		}//end switch
	}//end admin_columns

	/**
	 * set the ordering for the query
	 */
	public static function get_posts( &$query ) {
		if( $query->query_vars['post_type'] === static::$post_type ) {
			$query->set('orderby', 'menu_order');
			$query->set('order', 'asc');
		}//end if
	}//end get_posts
}//end class DJB\Admin\Post\Medals
