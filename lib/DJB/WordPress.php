<?php

namespace DJB;

class WordPress {
	public static function init() {
		self::register_taxonomies();
		self::register_post_types();

		add_action('admin_menu', array( 'DJB\Admin', 'admin_menu' ));
	}//end init

	public static function plugin() {
		static $plugin = null;

		if( ! $plugin ) {
			$plugin = new self;
		}//end if

		return $plugin;
	}//end plugin

	public static function plugin_dir( $path = null ) {
		static $plugin_dir;

		if( $path ) {
			$plugin_dir = $path;
		}//end if

		return $plugin_dir;
	}//end plugin_dir

	public static function register_post_types() {
		Admin\Orders::register();
		Admin\Ranks::register();
		Admin\Species::register();
	}//end register_custom_post_types

	public static function register_taxonomies() {
		Admin\Taxonomy\Path::register();
	}//end register_taxonomies

	public static function template_dir( $path = null ) {
		static $template_dir;

		if( $path ) {
			$template_dir = $path;
		}//end if

		return $template_dir;
	}//end template_idr
}//end class DJB\WordPress
