<?php

namespace DJB\Importer;

class Ranks extends \DJB\Importer {
	public $post_type = 'djb-rank';
	public $page_title = 'Ranks';

	public function data() {
		$sql = "
			SELECT name post_title,
						 abbr,
						 sort_order,
						 ordr order_id,
						 saberpoints saber_points,
						 handtohandpoints hand_to_hand_points,
						 forcepoints force_points,
						 skillpoints skill_points,
						 disciplinepoints discipline_points,
						 rank_id legacy_id
			  FROM ranks
			 ORDER BY sort_order
		";

		if( $data = \DJB::db('olddjb')->GetAll( $sql ) ) {
			foreach( $data as &$row ) {
				$slug = str_replace(' ', '-', strtolower( trim( $row['order_id'] ) ) );

				if( 'all' == $slug ) {
					unset( $row['order_id'] );
				} elseif( $order = \DJB\Core\Order::get( $slug ) ) {
					$row['order_id'] = $order->ID;
				} else {
					unset( $row['order_id'] );
				}//end else
			}//end foreach
		}//end if
		return $data;
	}//end data
}//end Ranks
