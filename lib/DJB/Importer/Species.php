<?php

namespace DJB\Importer;

class Species extends \DJB\Importer {
	public $post_type = 'djb-species';
	public $page_title = 'Species';

	public function data( $count = false ) {
		if( $count ) {
			return \DJB::db('olddjb')->GetOne("SELECT count(*) FROM options WHERE optionlistid = 6");
		}//end if

		$sql = "
			SELECT optionname post_title,
             optionid legacy_id
			  FROM options
			 WHERE optionlistid = 6
			 ORDER BY optionname
		";

		$data = \DJB::db('olddjb')->GetAll( $sql );
		return $data;
	}//end data
}//end Species
