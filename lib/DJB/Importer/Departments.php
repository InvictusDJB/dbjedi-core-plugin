<?php

namespace DJB\Importer;

class Departments extends \DJB\Importer {
	public $post_type = 'djb-department';
	public $page_title = 'Departments';

	public function data( $count = false ) {
		if( $count ) {
			return \DJB::db('olddjb')->GetOne("SELECT count(*) FROM sa_course_groups");
		}//end if

		$sql = "
			SELECT sa_course_group_id legacy_id,
			       name post_title,
						 member_id instructor_id
				FROM sa_course_groups
		";

		$data = \DJB::db('olddjb')->GetAll( $sql );
		return $data;
	}//end data
}//end Departments
