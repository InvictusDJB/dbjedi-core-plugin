<?php

namespace DJB\Admin;

abstract class Taxonomy {
	public static $taxonomy;
	public static $object_type;
	public static $args;

	public static function register() {
		register_taxonomy( static::$taxonomy, static::$object_type, static::$args );
	}//end register
}//end class DJB\Admin\Taxonomy
