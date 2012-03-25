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
			       sort_order,
			       group_sort,
			       description,
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
			$data[ $row['legacy_id'] ] = $row;
		}//end foreach

		$sql = "
			SELECT name post_title,
 						 abbr,
						 quantity,
						 medal_id legacy_id,
						 id
        FROM db_medal_upgrades
			 WHERE type = 'name'
		";

		$results = \DJB::db('djb')->Execute( $sql );

		foreach( $results as $row ) {
			$row['post_title'] = $data[ $row['legacy_id'] ]['post_title'] . ' with ' . $row['post_title'];
			$row['upgrade_type'] = 'none';
			$row['parent_legacy_id'] = $row['legacy_id'];
			$row = array_merge( $data[ $row['legacy_id'] ], $row );
			$data[] = $row;
		}//end foreach

		return $data;
	}//end data
}//end Medals
