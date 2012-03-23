<?php

namespace DJB\Admin;

class Orders {
	static $post_type = 'djb-order';
	static $class = 'DJB\Admin\Orders';

	/**
	 * Set up the columns that appear on the list page
	 */
	public static function admin_columns( $old_columns ) {
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['title'] = _x('Order', 'column name');
		$columns['path'] = __('Path');

		return $columns;
	}//end admin_columns

	/**
	 * echo the contents of custom columns defined in admin_columns
	 */
	public static function admin_custom_column( $column, $post_id ) {
		switch( $column ) {
			case 'path':
				$terms = wp_get_post_terms( $post_id, 'djb-path' );

				$out = '';
				foreach( $terms as $term ) {
					$out .= "{$term->name}, ";
				}//end foreach

				echo substr( $out, 0, -2 );
				break;
		}//end switch
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

	/**
	 * register the post types, actions, and filters
	 */
	public static function register() {
		$labels = array(
			'name' => _x('Orders', 'post type general name'),
			'singular_name' => _x('Orders', 'post type singular name'),
			'add_new' => _x('Add New', 'orders'),
			'add_new_item' => __('Add New Order'),
			'edit_item' => __('Edit Order'),
			'new_item' => __('New Order'),
			'all_items' => __('Orders'),
			'view_item' => __('View Orders'),
			'search_items' => __('Search Orders'),
			'not_found' => __('No orders found'),
			'not_found_in_trash' => __('No orders found in Trash'),
			'parent_item_colon' => '',
			'menu_name' => 'Orders',
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
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array(
				'title',
				'custom-fields',
			),
			'taxonomies' => array(
				'djb-path',
			),
		);

		register_post_type( static::$post_type, $args );

		register_taxonomy_for_object_type( 'djb-path', static::$post_type );

		add_filter('manage_edit-' . static::$post_type . '_columns', array( static::$class, 'admin_columns' ) );
		add_filter('manage_' . static::$post_type . '_posts_custom_column', array( static::$class, 'admin_custom_column' ), 10, 2 );

		add_action( 'pre_get_posts', array( static::$class, 'get_posts' ), 1 );
	}//end register
}//end class DJB\Orders
