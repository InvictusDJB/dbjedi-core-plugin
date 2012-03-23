<?php

namespace DJB\Admin;

class Ranks {
	static $post_type = 'djb-rank';
	static $class = 'DJB\Admin\Ranks';

	/**
	 * adds custom meta box
	 */
	public static function add_meta_boxes() {
		add_meta_box(
				'rank_meta_box'
			, 'Rank Data'
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

	public static function meta_box_html( $post ) {
		$fields = get_post_custom( $post->ID );
		$fields['abbr']                = esc_attr( $fields[ 'abbr' ][0] );
		$fields['order_id']            = esc_attr( $fields[ 'order_id' ][0] );
		$fields['sort_order']          = esc_attr( $fields[ 'sort_order' ][0] );
		$fields['discipline_points']   = esc_attr( $fields[ 'discipline_points' ][0] );
		$fields['force_points']        = esc_attr( $fields[ 'force_points' ][0] );
		$fields['hand_to_hand_points'] = esc_attr( $fields[ 'hand_to_hand_points' ][0] );
		$fields['saber_points']        = esc_attr( $fields[ 'saber_points' ][0] );
		$fields['skill_points']        = esc_attr( $fields[ 'skill_points' ][0] );

		$orders = new \DJB\Core\OrderCollection;

		// adds a nonce field that we can verify that it is set to avoid accidental saving
		// from an alternate source
		wp_nonce_field( 'rank_meta_box_nonce', 'meta_box_nonce' );

		include \DJB\WordPress::template_dir() . '/admin/ranks.meta-box.php';
	}//end meta_box_html

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
				/*'custom-fields',*/
			),
			'taxonomies' => array(
				'djb-order',
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
		if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'rank_meta_box_nonce' ) ) return;

		// if this isn't the right post type, bail
		if( static::$post_type != $_POST['post_type'] ) return;

		// if the user doesn't have permission to edit this post, bail
		if( !current_user_can( 'edit_post', $post_id ) ) return;

		$int_fields = array(
			'order_id',
			'sort_order',
			'discipline_points',
			'force_points',
			'hand_to_hand_points',
			'saber_points',
			'skill_points',
		);

		foreach( $int_fields as $field ) {
			if( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, $field, (int) ( $_POST[ $field ] ?: 0 ) );
			}//end if
		}//end foreach

		if( isset( $_POST['abbr'] ) ) {
			update_post_meta( $post_id, 'abbr', esc_attr( $_POST['abbr'] ) );
		}//end if
	}//end save
}//end class DJB\Ranks
