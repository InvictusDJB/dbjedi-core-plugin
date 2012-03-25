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
		$columns['title'] = _x('Medals', 'column name');
		$columns['abbr'] = __('Abbr');
		$columns['group_abbr'] = __('Group Abbr');
		$columns['quantity'] = __('#');
		$columns['sort_order'] = __('Sort Order');
		$columns['group_sort'] = __('Group Sort');
		$columns['sub'] = __('Children');

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
			case 'group_abbr':
				echo get_post_meta( $post_id, 'group_abbr', true );
				break;
			case 'group_sort':
				echo get_post_meta( $post_id, 'group_sort', true );
				break;
			case 'quantity':
				echo get_post_meta( $post_id, 'quantity', true );
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
			$meta = array(
				array(
					'key' => 'sort_order',
					'type' => 'numeric',
				),
				array(
					'key' => 'group_sort',
					'type' => 'numeric',
				),
				array(
					'key' => 'quantity',
					'type' => 'numeric',
				),
			);
			$query->set('orderby', 'meta_value');
			$query->set('meta_key', 'sort_order');
			$query->set('meta_value_num', true);
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
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array(
				'title',
				'custom-fields',
			),
		);

		register_post_type( static::$post_type, $args );

		add_filter('manage_edit-' . static::$post_type . '_columns', array( static::$class, 'admin_columns' ) );
		add_filter('manage_' . static::$post_type . '_posts_custom_column', array( static::$class, 'admin_custom_column' ), 10, 2 );

		add_action( 'pre_get_posts', array( static::$class, 'get_posts' ), 1 );
	}//end register
}//end class DJB\Admin\Medals
