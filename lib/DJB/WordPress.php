<?php

namespace DJB;

class WordPress {
	public static function base_dir( $path = null ) {
		static $dir;

		if( $path ) {
			$dir = $path;
		}//end if

		return $dir;
	}//end base_dir

	public static function images( $what = null, $path = null ) {
		static $images = array();

		if( is_array( $what ) ) {
			$images = array_merge( $images, $what );
		} else {
			if( $path ) {
				$images[ $what ] = $path;
			} else {
				return $images[ $what ];
			}//end else
		}//end else

	}//end root_idr

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

			static::template_dir( $plugin_dir . '/templates' );
			static::base_dir( dirname( dirname( dirname( $plugin_dir ) ) ) );
			static::root_dir( dirname( static::base_dir() ) );
		}//end if

		return $plugin_dir;
	}//end plugin_dir

	public static function register_post_types() {
		$types = Admin::importers();

		foreach( $types as $type ) {
			if( 'Users' == $type ) continue;

			$class = "\DJB\Admin\\" . $type;
			$class::register();
		}//end foreach
	}//end register_custom_post_types

	public static function register_taxonomies() {
		Admin\Taxonomy\Department::register();
		Admin\Taxonomy\Path::register();
	}//end register_taxonomies

	public static function root_dir( $path = null ) {
		static $dir;

		if( $path ) {
			$dir = $path;
		}//end if

		return $dir;
	}//end root_idr

	public static function template_dir( $path = null ) {
		static $template_dir;

		if( $path ) {
			$template_dir = $path;
		}//end if

		return $template_dir;
	}//end template_idr
}//end class DJB\WordPress
