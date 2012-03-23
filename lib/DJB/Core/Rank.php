<?php

namespace DJB\Core;

class Rank extends \DJB\DataObject {
	public static $post_type = 'djb-rank';

	public function order() {
		static $order = null;

		// grab the order id
		$order_id = $this->meta('order_id');

		// was there an order_id and if so has the static order array not yet been set?
		if( $order_id && ! $order[ $order_id ] ) {
			$order[ $order_id ] = \DJB\Core\Order::get( $order_id );
		}//end if

		return $order[ $order_id ];
	}//end order
}//end class DJB\Core\Rank
