<?php

namespace DJB\Importer;

class Positions extends \DJB\Importer {
	public $post_type = 'djb-position';
	public $page_title = 'Positions';

	public function data( $count = false ) {
		if( $count ) {
			return \DJB::db('olddjb')->GetOne("SELECT count(*) FROM positions WHERE positiontype IN ('Assistant', 'Main Body', 'Tribune', 'Chamber of Justice', 'Society Leader') AND sort_order < 999");
		}//end if

		$sql = "
			SELECT name post_title,
						 position_id legacy_id,
						 abbr,
						 description post_content,
						 positiontype type,
						 sort_order menu_order
			  FROM positions
			 WHERE positiontype IN ('Main Body','Tribune', 'Chamber of Justice', 'Society Leader')
			   AND sort_order < 999
			 ORDER BY sort_order
		";

		$results = \DJB::db('olddjb')->Execute( $sql );
		$data = array();

		foreach( $results as $row ) {
			$data[ $row['abbr'] ] = $row;
		}//end foreach

		$sql = "
			SELECT name post_title,
						 position_id legacy_id,
						 abbr,
						 description post_content,
						 positiontype type,
						 sort_order menu_order
			  FROM positions
			 WHERE positiontype = 'Assistant'
			   AND sort_order < 999
			 ORDER BY sort_order
		";

		$results = \DJB::db('olddjb')->Execute( $sql );

		foreach( $results as $row ) {
			$parent_abbr = null;

			if( 'Wiki' == $row['abbr'] ) {
				$parent_abbr = 'T:W';
			} elseif( 'FIC' == $row['abbr'] ) {
				$parent_abbr = 'T:F';
			} elseif( 'DCM' == $row['abbr'] ) {
				$parent_abbr = 'CM';
			} elseif( 'GAM' == $row['abbr'] ) {
				$parent_abbr = 'T:G';
			} elseif( in_array( $row['abbr'], array('PROF', 'A:PROF', 'DOC') ) ) {
				$parent_abbr = 'HM';
			} else {
				preg_match('/.+:(.+)/', $row['abbr'], $matches );
				$parent_abbr = $matches[1];

				$parent_abbr = $parent_abbr == 'V' ? 'VOICE' : $parent_abbr;
				$parent_abbr = $parent_abbr == 'F' ? 'FIST' : $parent_abbr;
			}//end else

			if( $parent_abbr ) {
				$row['parent_legacy_id'] = $data[ $parent_abbr ]['legacy_id'];
				$data[] = $row;
			}//end if
		}//end foreach

		return $data;
	}//end data
}//end Positions
