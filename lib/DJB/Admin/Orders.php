<?php

namespace DJB\Admin;

class Orders {
	static $post_type = 'djb-order';
	static $class = 'DJB\Admin\Orders';

	/**
	 * adds custom meta box
	 */
	public static function add_meta_boxes() {
		add_meta_box(
				'order_meta_box'
			, 'Order Data'
			, array( static::$class, 'meta_box_html' )
			, static::$post_type
			, 'normal'
			, 'high'
		);
	}//end meta_boxes

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
				echo ucfirst( get_post_meta( $post_id, 'path', true ) );
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

	public static function meta_box_html( $post ) {
		$fields = get_post_custom( $post->ID );
		$fields['path'] = esc_attr( $fields[ 'path' ][0] );

		// adds a nonce field that we can verify that it is set to avoid accidental saving
		// from an alternate source
		wp_nonce_field( 'order_meta_box_nonce', 'meta_box_nonce' );

		include \DJB\WordPress::template_dir() . '/admin/order.meta-box.php';
	}//end meta_box_html

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
			),
		);

		register_post_type( static::$post_type, $args );

		add_filter('manage_edit-' . static::$post_type . '_columns', array( static::$class, 'admin_columns' ) );
		add_filter('manage_' . static::$post_type . '_posts_custom_column', array( static::$class, 'admin_custom_column' ), 10, 2 );

		add_action( 'pre_get_posts', array( static::$class, 'get_posts' ), 1 );

		add_action( 'add_meta_boxes', array( static::$class, 'add_meta_boxes' ) );
		add_action( 'save_post', array( static::$class, 'save' ) );
	}//end register

	public static function save( $post_id ) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our nonce isn't there, or we can't verify it, bail
		if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'order_meta_box_nonce' ) ) return;

		// if this isn't the right post type, bail
		if( static::$post_type != $_POST['post_type'] ) return;

		// if the user doesn't have permission to edit this post, bail
		if( !current_user_can( 'edit_post', $post_id ) ) return;

		if( isset( $_POST['path'] ) ) {
			update_post_meta( $post_id, 'path', esc_attr( $_POST['path'] ) );
		}//end if
	}//end save
}//end class DJB\Orders
