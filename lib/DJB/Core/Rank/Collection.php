<?php

namespace DJB\Core\Rank;

class Collection extends \DJB\Core\Collection {
	public static $child = '\DJB\Core\Rank';

	public function get( $args = array() ) {
		$args['post_type'] = $this->post_type();

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
}//end class DJB\Core\Rank\Collection
