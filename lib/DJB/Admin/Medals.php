<?php

namespace DJB\Admin;

class Medals {
	static $post_type = 'djb-medal';
	static $class = 'DJB\Admin\Medals';

	/**
	 * Set up the columns that appear on the list page
	 */
	public static function admin_columns( $old_columns ) {
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['img'] = __('');
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
			case 'img':
				$image = get_post_meta( $post_id, 'logo', true );
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
		//die( \DJB::dbug( $query ) );
	}//end get_posts

	/**
	 * register the post types, actions, and filters
	 */
	public static function register() {
		$labels = array(
			'name' => _x('Medals', 'post type general name'),
			'singular_name' => _x('Medals', 'post type singular name'),
			'add_new' => _x('Add New', 'medals'),
			'add_new_item' => __('Add New Medals'),
			'edit_item' => __('Edit Medals'),
			'new_item' => __('New Medals'),
			'all_items' => __('Medals'),
			'view_item' => __('View Medals'),
			'search_items' => __('Search Medals'),
			'not_found' => __('No medals found'),
			'not_found_in_trash' => __('No medals found in Trash'),
			'parent_item_colon' => '',
			'menu_name' => 'Medals',
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'menu_icon' => null,
			'show_ui' => true,
			'show_in_menu' => 'djb-data',
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => true,
			'menu_position' => null,
			'supports' => array(
				'title',
				'custom-fields',
				'page-attributes',
			),
		);

		register_post_type( static::$post_type, $args );

		add_filter('manage_edit-' . static::$post_type . '_columns', array( static::$class, 'admin_columns' ) );
		add_filter('manage_' . static::$post_type . '_posts_custom_column', array( static::$class, 'admin_custom_column' ), 10, 2 );

		add_action( 'pre_get_posts', array( static::$class, 'get_posts' ), 1 );
	}//end register
}//end class DJB\Admin\Medals
