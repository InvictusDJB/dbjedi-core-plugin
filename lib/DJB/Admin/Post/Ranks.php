<?php

namespace DJB\Admin\Post;

class Ranks extends \DJB\Admin\Post {
	public static $post_type = 'djb-rank';
	public static $plural = 'Ranks';
	public static $singular = 'Rank';
	public static $supports = array(
		'title',
		'page-attributes',
	);

	/**
	 * adds custom meta box
	 */
	public static function add_meta_boxes() {
		add_meta_box(
				'rank_meta_box'
			, 'Rank Data'
			, array( get_called_class(), 'meta_box_html' )
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
			case 'order_id':
				echo self::order( get_post_meta( $post_id, 'order_id', true ) );
				break;
			case 'menu_order':
				echo $post->menu_order;
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
}//end class DJB\Admin\Post\Ranks
