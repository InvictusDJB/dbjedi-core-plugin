<?php

namespace DJB\Importer;

class Positions extends \DJB\Importer {
	public $post_type = 'djb-position';
	public $page_title = 'Positions';

	public function data( $count = false ) {
		if( $count ) {
			return \DJB::db('olddjb')->GetOne("SELECT count(*) FROM positions WHERE positiontype IN ('Assistant', 'Main Body')");
		}//end if

		$sql = "
			SELECT name post_title,
						 position_id legacy_id,
						 abbr,
						 description post_content,
						 sort_order menu_order
			  FROM member_titles
			 WHERE positiontype = 'Main Body'
			   AND sort_order <> 999
			 ORDER BY sort_order
		";

		$results = \DJB::db('djb')->Execute( $sql );
		$data = array();

		foreach( $results as $row ) {
			$data[ $row['legacy_id'] ] = $row;
		}//end foreach

		$sql = "
			SELECT name post_title,
						 position_id parent_legacy_id,
						 abbr,
						 description post_content,
						 sort_order menu_order
			  FROM member_titles
			 WHERE positiontype = 'Assistant'
			   AND sort_order <> 999
			 ORDER BY sort_order
		";

		$results = \DJB::db('djb')->Execute( $sql );

		foreach( $results as $row ) {
			$data[] = $row;
		}//end foreach

		return $data;
	}//end data
}//end Positions
