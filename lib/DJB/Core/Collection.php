<?php

namespace DJB\Core;

abstract class Collection extends \DJB\Collection {
	/**
	 * WordPress query object
	 */
	protected $query;

	public function get( $args = array() ) {
	}//end get

	public function post_type() {
		$child = static::$child;
		return $child::$post_type;
	}//end post_type
}//end class DJB\Core\Collection
