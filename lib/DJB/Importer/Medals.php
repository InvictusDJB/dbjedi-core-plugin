<?php

namespace DJB\Importer;

class Medals extends \DJB\Importer {
	public $post_type = 'djb-medal';
	public $page_title = 'Medals';

	public function data() {
		$sql = "
			SELECT name post_title,
			       id legacy_id,
			       abbr,
			       group_abbr,
			       sort_order menu_order,
			       group_sort,
			       logo,
			       status_id,
						 type_id,
						 0 quantity,
						 (SELECT distinct type FROM db_medal_upgrades u WHERE medal_id = m.id) upgrade_type
			  FROM db_medal m
			 ORDER BY sort_order, group_sort
		";

		$results = \DJB::db('djb')->Execute( $sql );
		$data = array();

		foreach( $results as $row ) {
			if( ! $row['group_abbr'] ) {
				unset( $row['group_abbr'] );
			}//end if

			if( ! $row['group_sort'] ) {
				unset( $row['group_sort'] );
			}//end if

			$data[ $row['legacy_id'] ] = $row;
		}//end foreach

		$sql = "
			SELECT name post_title,
 						 abbr,
						 quantity,
						 medal_id parent_legacy_id,
						 quantity menu_order
        FROM db_medal_upgrades
			 WHERE type = 'name'
		";

		$results = \DJB::db('djb')->Execute( $sql );

		foreach( $results as $row ) {
			$row['post_title'] = $data[ $row['parent_legacy_id'] ]['post_title'] . ' with ' . $row['post_title'];
			$row['upgrade_type'] = 'none';
			$row = array_merge( $data[ $row['parent_legacy_id'] ], $row );
			unset( $row['legacy_id'] );
			$data[] = $row;
		}//end foreach

		return $data;
	}//end data
}//end Medals
