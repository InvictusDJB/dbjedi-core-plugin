<?php

namespace DJB\Importer;

class Titles extends \DJB\Importer {
	public $post_type = 'djb-title';
	public $page_title = 'Titles';

	public function data( $count = false ) {
		if( $count ) {
			return \DJB::db('olddjb')->GetOne("SELECT count(*) FROM member_titles");
		}//end if

		$sql = "
			SELECT title post_title,
						 titleid legacy_id,
						 titlefemale female_title,
						 sort_order menu_order
			  FROM member_titles
			 ORDER BY sort_order
		";

		$data = \DJB::db('olddjb')->GetAll( $sql );
		return $data;
	}//end data
}//end Titles
