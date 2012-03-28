<?php

namespace DJB\Admin;

class Orders extends Post {
	public static $post_type = 'djb-order';
	public static $plural = 'Orders';
	public static $singular = 'Order';

	/**
	 * adds custom meta box
	 */
	public static function add_meta_boxes() {
		add_meta_box(
				'order_meta_box'
			, 'Order Data'
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

	public static function meta_box_html( $post ) {
		$fields = get_post_custom( $post->ID );
		$fields['path'] = esc_attr( $fields[ 'path' ][0] );

		// adds a nonce field that we can verify that it is set to avoid accidental saving
		// from an alternate source
		wp_nonce_field( 'order_meta_box_nonce', 'meta_box_nonce' );

		include \DJB\WordPress::template_dir() . '/admin/order.meta-box.php';
	}//end meta_box_html

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

	public static function supports() {
		return array(
			'title',
		);
	}//end supports
}//end class DJB\Orders
