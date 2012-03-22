<?php

namespace DJB\Core;

class Rank extends \DJB\DataObject {
	public static $post_type = 'djb-rank';

	public function order() {
		static $order = null;

		if( ! $order ) {
			$order = \DJB\Core\Order::get( $this->meta( 'order_id' ) );
		}//end if

		return $order;
	}//end order
}//end class DJB\Core\Rank
