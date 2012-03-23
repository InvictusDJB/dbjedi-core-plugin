<?php

namespace DJB\Admin;

class Ranks {
	static $post_type = 'djb-rank';
	static $class = 'DJB\Admin\Ranks';

	/**
	 * Set up the columns that appear on the list page
	 */
	public static function admin_columns( $old_columns ) {
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['title'] = _x('Ranks', 'column name');
		$columns['abbr'] = __('Abbreviation');
		$columns['order_id'] = __('Order');
		$columns['sort_order'] = __('Sort Order');

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
			case 'order_id':
				echo self::order( get_post_meta( $post_id, 'order_id', true ) );
				break;
			case 'sort_order':
				echo get_post_meta( $post_id, 'sort_order', true );
				break;
		}//end switch
	}//end admin_columns

	/**
	 * set the ordering for the query
	 */
	public static function get_posts( &$query ) {
		if( $query->query_vars['post_type'] === static::$post_type ) {
			$query->set('meta_key', 'sort_order');
			$query->set('orderby', 'meta_value_num');
			$query->set('order', 'asc');
		}//end if
	}//end get_posts

	/**
	 * return the Order name for a given order_id
	 */
	public static function order( $order_id ) {
		$order = \DJB\Core\Order::get( $order_id );

		return $order ? $order->post_title: 'All';
	}//end order

	/**
	 * register the post types, actions, and filters
	 */
	public static function register() {
		$labels = array(
			'name' => _x('Ranks', 'post type general name'),
			'singular_name' => _x('Ranks', 'post type singular name'),
			'add_new' => _x('Add New', 'ranks'),
			'add_new_item' => __('Add New Rank'),
			'edit_item' => __('Edit Rank'),
			'new_item' => __('New Rank'),
			'all_items' => __('Ranks'),
			'view_item' => __('View Ranks'),
			'search_items' => __('Search Ranks'),
			'not_found' => __('No ranks found'),
			'not_found_in_trash' => __('No ranks found in Trash'),
			'parent_item_colon' => '',
			'menu_name' => 'Ranks',
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
				'djb-order',
			),
		);

		register_post_type( static::$post_type, $args );

		add_filter('manage_edit-' . static::$post_type . '_columns', array( static::$class, 'admin_columns' ) );
		add_filter('manage_' . static::$post_type . '_posts_custom_column', array( static::$class, 'admin_custom_column' ), 10, 2 );

		add_action( 'pre_get_posts', array( static::$class, 'get_posts' ), 1 );

		add_action( 'add_meta_boxes', array( static::$class, 'meta_boxes' ) );
	}//end register

}//end class DJB\Ranks
