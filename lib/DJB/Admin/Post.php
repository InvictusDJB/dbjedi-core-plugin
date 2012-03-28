<?php

namespace DJB\Admin;

abstract class Post {
	public static $post_type = 'djb-REPLACE';
	public static $plural = 'REPLACE';
	public static $singular = 'REPLACE';
	public static $parent_menu = 'djb-data';
	public static $supports = array(
		'title',
		'revisions',
	);
	public static $taxonomies = array();
	public static $hierarchical = false;

	/**
	 * set the ordering for the query
	 */
	public static function get_posts( &$query ) {
		if( $query->query_vars['post_type'] === static::$post_type ) {
			$query->set('orderby', 'title');
			$query->set('order', 'desc');
		}//end if
	}//end get_posts

	/**
	 * register the post types, actions, and filters
	 */
	public static function register() {
		$class = get_called_class();

		$labels = array(
			'name' => _x( static::$plural , 'post type general name'),
			'singular_name' => _x( static::$singular , 'post type singular name'),
			'add_new' => __('Add New'),
			'add_new_item' => __('Add New ' . static::$singular),
			'edit_item' => __('Edit ' . static::$plural ),
			'new_item' => __('New ' . static::$singular),
			'all_items' => __(static::$plural),
			'view_item' => __('View ' . static::$plural),
			'search_items' => __('Search ' . static::$plural),
			'not_found' => __('No '.strtolower( static::$plural ).' found'),
			'not_found_in_trash' => __('No '.strtolower( static::$plural ).' found in Trash'),
			'parent_item_colon' => '',
			'menu_name' => static::$plural,
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'menu_icon' => null,
			'show_ui' => true,
			'show_in_menu' => static::$parent_menu,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => static::$hierarchical,
			'menu_position' => null,
			'supports' => static::$supports,
			'taxonomies' => static::$taxonomies,
		);

		register_post_type( static::$post_type, $args );

		add_action( 'pre_get_posts', array( $class, 'get_posts' ), 1 );

		if( method_exists( $class, 'admin_columns' ) ) {
			add_filter('manage_edit-' . static::$post_type . '_columns', array( $class, 'admin_columns' ) );
		}//end if

		if( method_exists( $class, 'admin_custom_column' ) ) {
			add_filter('manage_' . static::$post_type . '_posts_custom_column', array( $class, 'admin_custom_column' ), 10, 2 );
		}//end if

		if( method_exists( $class, 'add_meta_boxes' ) ) {
			add_action( 'add_meta_boxes', array( $class, 'add_meta_boxes' ) );
		}//end if

		if( method_exists( $class, 'save' ) ) {
			add_action( 'save_post', array( $class, 'save' ) );
		}//end if
	}//end register
}//end class DJB\Admin\Post
