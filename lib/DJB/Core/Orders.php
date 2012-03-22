<?php

namespace DJB\Core;

class Orders extends \DJB\Collection {
	static $child = '\DJB\Core\Order';
	static $post_type = 'djb-order';

	public function get( $args = array() ) {
		$args['post_type'] = static::$post_type;

		$defaults = array(
			'orderby' => 'post_title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		);

		$args = array_merge( $defaults, $args );

		$this->query = new \WP_Query( $args );

		return $this->query->posts;
	}//end get
}//end class DJB\Core\Orders
